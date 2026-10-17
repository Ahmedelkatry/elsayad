<?php
/**
 * ZK IP Mediator Script (وسيط البصمة للفروع بنظام IP المباشر)
 * 
 * هذا السكربت يعمل على أجهزة الكمبيوتر في الفروع:
 * 1. يتصل بماكينة البصمة محلياً عبر عنوان الـ IP والمنفذ 4370 (Direct IP Socket)
 * 2. يسحب سجلات الحضور والانصراف الجديدة بدون الحاجة لـ ADMS في الماكينة
 * 3. يرسل البيانات بتنسيق JSON إلى السيرفر الرئيسي للمشروع عبر الرابط: /api/zkt/upload
 * 4. يرسل إشارة نبض (Heartbeat) دورية لتحديث حالة الجهاز في لوحة التحكم (Online)
 * 
 * الاستخدام:
 *   php zk_mediator.php [options]
 * 
 * الخيارات:
 *   --loop     تشغيل مستمر كخدمة في الخلفية كل 60 ثانية.
 *   --force    إعادة رفع كل السجلات وتجاهل السجلات المرفوعة سابقاً.
 *   --debug    عرض تفاصيل الاتصال والردود القادمة من السيرفر.
 *   --help     عرض تعليمات الاستخدام.
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

define('CONFIG_FILE', __DIR__ . '/config.json');
define('STATE_FILE', __DIR__ . '/state.json');

// تحميل التوقيت الافتراضي
date_default_timezone_set('Africa/Cairo');

// --- 1. Custom Autoloader for Rats\Zkteco Library ---
spl_autoload_register(function ($class) {
    $prefix = 'Rats\\Zkteco\\';
    $base_dir = __DIR__ . '/lib/';
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }
    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
});

use Rats\Zkteco\Lib\ZKTeco;

// --- 2. Parse Command Line Arguments ---
$options = getopt('', ['loop', 'force', 'debug', 'help', 'sync-users', 'force-sync']);
if (isset($options['help'])) {
    echo "========================================================\n";
    echo "       ZK IP Mediator - Branch Attendance Sync Tool     \n";
    echo "========================================================\n\n";
    echo "Usage:\n";
    echo "  php zk_mediator.php [options]\n\n";
    echo "Options:\n";
    echo "  --loop         Run continuously as a loop/daemon.\n";
    echo "  --force        Force sync all logs, ignoring state.json.\n";
    echo "  --debug        Show verbose debug information.\n";
    echo "  --sync-users   Pull new employees from server and push them to the ZK device.\n";
    echo "  --force-sync   Re-push ALL employees to device (not just new ones).\n";
    echo "  --help         Show this help message.\n";
    exit(0);
}

$isLoop       = isset($options['loop']);
$isForce      = isset($options['force']);
$isDebug      = isset($options['debug']);
$isSyncUsers  = isset($options['sync-users']) || isset($options['force-sync']);
$isForceSync  = isset($options['force-sync']);

logMessage("INFO", "Starting ZK IP Mediator (Direct IP to Project API)...");

// --- 3. Main Loop ---
do {
    // Reload config every loop to allow dynamic changes
    if (!file_exists(CONFIG_FILE)) {
        logMessage("ERROR", "Configuration file config.json not found! Please create it.");
        if ($isLoop) {
            sleep(30);
            continue;
        } else {
            exit(1);
        }
    }

    $config = json_decode(file_get_contents(CONFIG_FILE), true);
    if (!$config || !isset($config['server_url']) || !isset($config['devices'])) {
        logMessage("ERROR", "Invalid config.json format! Must include 'server_url' and 'devices'.");
        if ($isLoop) {
            sleep(30);
            continue;
        } else {
            exit(1);
        }
    }

    if (!empty($config['timezone'])) {
        @date_default_timezone_set($config['timezone']);
    }

    $serverUrl = rtrim($config['server_url'], '/');
    // Strip trailing iclock or api if accidentally added in config
    $serverUrl = preg_replace('#/(cdata/)?iclock(/cdata|/getrequest|/devicecmd)?$#i', '', $serverUrl);
    $serverUrl = preg_replace('#/api/zkt/(upload|ping)$#i', '', $serverUrl);

    $syncInterval = isset($config['sync_interval_seconds']) ? max(10, (int)$config['sync_interval_seconds']) : 60;
    $verifySsl = isset($config['verify_ssl']) ? (bool)$config['verify_ssl'] : false;
    $devices = $config['devices'];

    // Load state
    $state = [];
    if (file_exists(STATE_FILE) && !$isForce) {
        $state = json_decode(file_get_contents(STATE_FILE), true) ?: [];
    }

    foreach ($devices as $deviceConfig) {
        $ip = trim($deviceConfig['ip'] ?? '');
        $port = (int)($deviceConfig['port'] ?? 4370);
        $name = trim($deviceConfig['name'] ?? $ip);
        $deviceKey = !empty($ip) ? $ip : $name;

        if (empty($ip)) {
            logMessage("WARNING", "Device configuration missing IP address. Skipping.");
            continue;
        }

        logMessage("INFO", "Processing device: $name (IP: $ip:$port)");

        // 1. Connect to ZKTeco Fingerprint Machine via Direct IP Socket
        $zk = new ZKTeco($ip, $port);
        logMessage("DEBUG", "Connecting via IP socket to $ip:$port...");

        if (!@$zk->connect()) {
            logMessage("ERROR", "Could not connect to device: $name ($ip:$port). Check IP, power, and LAN cable.");
            // Send offline status or record failure
            $state[$deviceKey]['last_sync_attempt'] = date('Y-m-d H:i:s');
            $state[$deviceKey]['status'] = 'connection_failed';
            file_put_contents(STATE_FILE, json_encode($state, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
            continue;
        }

        logMessage("INFO", "Connected successfully to device: $name");

        // 2. Fetch Attendance Records from the device
        $logs = $zk->getAttendance();
        if ($logs === false) {
            logMessage("ERROR", "Failed to retrieve attendance logs from device: $name");
            $zk->disconnect();
            continue;
        }

        $logCount = is_array($logs) ? count($logs) : 0;
        logMessage("INFO", "Device has $logCount total records stored in memory.");

        // Disconnect immediately after reading data to free the machine socket
        $zk->disconnect();
        logMessage("DEBUG", "Disconnected from device socket: $name");

        // 3. Filter New Logs
        $lastSyncedTime = isset($state[$deviceKey]['last_synced_time']) ? $state[$deviceKey]['last_synced_time'] : null;
        if ($lastSyncedTime) {
            logMessage("DEBUG", "Last synced timestamp: $lastSyncedTime");
        } else {
            logMessage("DEBUG", "No previous sync history. Processing all available records.");
        }

        $newLogs = [];
        $latestTimestamp = $lastSyncedTime;

        if ($logCount > 0) {
            // Sort ascending by timestamp
            usort($logs, function ($a, $b) {
                return strcmp($a['timestamp'] ?? '', $b['timestamp'] ?? '');
            });

            foreach ($logs as $log) {
                if (empty($log['timestamp']) || empty($log['id'])) {
                    continue;
                }

                // If not forcing, ignore logs older than or equal to last synced timestamp
                if (!$isForce && $lastSyncedTime && strcmp($log['timestamp'], $lastSyncedTime) <= 0) {
                    continue;
                }

                $newLogs[] = [
                    'id'        => trim($log['id']),
                    'timestamp' => trim($log['timestamp']),
                    'type'      => (int)($log['type'] ?? 0) // 0=in, 1=out, 3=breakin, 4=breakout
                ];

                if (empty($latestTimestamp) || strcmp($log['timestamp'], $latestTimestamp) > 0) {
                    $latestTimestamp = $log['timestamp'];
                }
            }
        }

        $newLogCount = count($newLogs);
        $uploadUrl = "{$serverUrl}/api/zkt/upload";

        if ($newLogCount > 0) {
            logMessage("INFO", "Found $newLogCount NEW log(s) to upload for $name.");

            // 4. Send JSON Payload to Laravel Project API
            $payload = json_encode([
                'ip'   => $ip,
                'name' => $name,
                'logs' => $newLogs
            ], JSON_UNESCAPED_UNICODE);

            $res = sendHttpRequest($uploadUrl, $payload, $verifySsl, $isDebug);

            if ($res['success']) {
                $body = json_decode($res['body'], true);
                if (isset($body['status']) && $body['status'] === 'success') {
                    $syncedCount = $body['synced_count'] ?? $newLogCount;
                    logMessage("INFO", "SUCCESS: Uploaded $syncedCount logs to project for $name.");

                    // Update local state
                    $state[$deviceKey] = [
                        'device_name'       => $name,
                        'ip'                => $ip,
                        'last_synced_time'  => $latestTimestamp,
                        'last_sync_attempt' => date('Y-m-d H:i:s'),
                        'status'            => 'success',
                        'synced_count'      => $syncedCount
                    ];
                    file_put_contents(STATE_FILE, json_encode($state, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
                } else {
                    $errMsg = $body['message'] ?? $res['body'];
                    logMessage("ERROR", "Server responded with error: $errMsg");
                }
            } else {
                logMessage("ERROR", "Failed to upload logs to server: " . $res['error']);
            }
        } else {
            logMessage("INFO", "No new logs to upload for $name. Sending heartbeat ping to keep device Online.");

            // Send Heartbeat so the web dashboard knows the device is connected
            $heartbeatPayload = json_encode([
                'ip'   => $ip,
                'name' => $name,
                'logs' => []
            ], JSON_UNESCAPED_UNICODE);

            $res = sendHttpRequest($uploadUrl, $heartbeatPayload, $verifySsl, $isDebug);

            if ($res['success']) {
                logMessage("DEBUG", "Heartbeat received by server successfully.");
                $state[$deviceKey]['device_name'] = $name;
                $state[$deviceKey]['ip'] = $ip;
                $state[$deviceKey]['last_sync_attempt'] = date('Y-m-d H:i:s');
                $state[$deviceKey]['status'] = 'online_no_new_data';
                file_put_contents(STATE_FILE, json_encode($state, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
            } else {
                logMessage("WARNING", "Heartbeat failed: " . $res['error']);
            }
        }

        echo "\n";
    }

    // ── مزامنة الموظفين مع ماكينة البصمة (إضافة الجدد وحذف المحذوفين) ──
    if ($isSyncUsers) {
        syncDeletedUsersToDevices($config, $verifySsl, $isDebug);
        syncUsersToDevices($config, $verifySsl, $isDebug, $isForceSync);
    }

    if ($isLoop) {
        logMessage("INFO", "Cycle finished. Sleeping for {$syncInterval} seconds before next check...");
        sleep($syncInterval);
    }

} while ($isLoop);

logMessage("INFO", "ZK IP Mediator finished execution.");

/**
 * ══════════════════════════════════════════════════════════════════
 * مزامنة الموظفين من السيرفر إلى ماكينة البصمة مباشرةً عبر الـ IP
 * يُشغَّل بأمر: php zk_mediator.php --sync-users
 * أو في الحلقة التلقائية مع: php zk_mediator.php --loop --sync-users
 * ══════════════════════════════════════════════════════════════════
 */
function syncUsersToDevices(array $config, bool $verifySsl, bool $isDebug, bool $forceAll = false): void
{
    $serverUrl = rtrim($config['server_url'], '/');
    $devices   = $config['devices'];

    foreach ($devices as $deviceConfig) {
        $ip   = trim($deviceConfig['ip'] ?? '');
        $port = (int)($deviceConfig['port'] ?? 4370);
        $name = trim($deviceConfig['name'] ?? $ip);

        if (empty($ip)) {
            continue;
        }

        logMessage("INFO", "[USER-SYNC] Fetching employees from server for: $name");

        // 1. جلب قائمة الموظفين من السيرفر
        $params = http_build_query([
            'device_ip'   => $ip,
            'device_name' => $name,
            'force'       => $forceAll ? '1' : '0',
        ]);
        $usersUrl = "{$serverUrl}/api/zkt/users?{$params}";

        $ch = curl_init($usersUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $verifySsl);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, $verifySsl ? 2 : 0);
        if ($isDebug) curl_setopt($ch, CURLOPT_VERBOSE, true);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlErr  = curl_error($ch);
        curl_close($ch);

        if ($response === false || $httpCode < 200 || $httpCode >= 300) {
            logMessage("ERROR", "[USER-SYNC] Failed to fetch employees from server. HTTP=$httpCode err=$curlErr");
            continue;
        }

        $data = json_decode($response, true);
        if (!$data || $data['status'] !== 'success') {
            logMessage("ERROR", "[USER-SYNC] Invalid response from server.");
            continue;
        }

        $employees = $data['employees'] ?? [];
        $total     = count($employees);

        if ($total === 0) {
            logMessage("INFO", "[USER-SYNC] No new employees to sync for $name.");
            continue;
        }

        logMessage("INFO", "[USER-SYNC] Found $total employee(s) to push to $name ($ip:$port).");

        // 2. الاتصال بالماكينة
        $zk = new ZKTeco($ip, $port);
        if (!@$zk->connect()) {
            logMessage("ERROR", "[USER-SYNC] Cannot connect to device $name ($ip:$port). Skipping.");
            continue;
        }

        $zk->disableDevice();

        $syncedNums = [];
        $failedCount = 0;

        foreach ($employees as $emp) {
            $uid      = (int)   ($emp['uid']      ?? 0);
            $userid   = (string)($emp['userid']   ?? $uid);
            $empName  = (string)($emp['name']     ?? '');
            $password = (string)($emp['password'] ?? '');
            $role     = (int)   ($emp['role']     ?? 0);

            if ($uid <= 0) {
                logMessage("WARNING", "[USER-SYNC] Skipping employee with invalid UID: $empName");
                continue;
            }

            $result = $zk->setUser($uid, $userid, $empName, $password, $role);

            if ($result !== false) {
                $syncedNums[] = $userid;
                logMessage("INFO", "[USER-SYNC] ✔ Pushed: [$uid] $empName");
            } else {
                $failedCount++;
                logMessage("WARNING", "[USER-SYNC] ✘ Failed to push: [$uid] $empName");
            }
        }

        $zk->enableDevice();
        $zk->disconnect();

        logMessage("INFO", "[USER-SYNC] Done for $name: " . count($syncedNums) . " synced, $failedCount failed.");

        // 3. إبلاغ السيرفر بالأرقام التي تمت مزامنتها
        if (!empty($syncedNums)) {
            $confirmPayload = json_encode([
                'device_name' => $name,
                'device_ip'   => $ip,
                'synced_nums' => $syncedNums,
            ]);
            $confirmUrl     = "{$serverUrl}/api/zkt/users/synced";
            $res = sendHttpRequest($confirmUrl, $confirmPayload, $verifySsl, $isDebug);
            if ($res['success']) {
                logMessage("INFO", "[USER-SYNC] Server confirmed sync for " . count($syncedNums) . " employee(s) on device $name.");
            } else {
                logMessage("WARNING", "[USER-SYNC] Failed to confirm sync with server: " . $res['error']);
            }
        }
    }
}

/**
 * ══════════════════════════════════════════════════════════════════
 * حذف الموظفين المحذوفين من السيرفر من ماكينة البصمة مباشرةً عبر الـ IP
 * ══════════════════════════════════════════════════════════════════
 */
function syncDeletedUsersToDevices(array $config, bool $verifySsl, bool $isDebug): void
{
    $serverUrl = rtrim($config['server_url'], '/');
    $devices   = $config['devices'];

    foreach ($devices as $deviceConfig) {
        $ip   = trim($deviceConfig['ip'] ?? '');
        $port = (int)($deviceConfig['port'] ?? 4370);
        $name = trim($deviceConfig['name'] ?? $ip);

        if (empty($ip)) {
            continue;
        }

        // 1. جلب قائمة الموظفين المطلوب حذفهم من السيرفر
        $params = http_build_query([
            'device_ip'   => $ip,
            'device_name' => $name,
        ]);
        $deletedUrl = "{$serverUrl}/api/zkt/users/deleted?{$params}";

        $ch = curl_init($deletedUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $verifySsl);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, $verifySsl ? 2 : 0);
        if ($isDebug) curl_setopt($ch, CURLOPT_VERBOSE, true);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlErr  = curl_error($ch);
        curl_close($ch);

        if ($response === false || $httpCode < 200 || $httpCode >= 300) {
            if ($isDebug) {
                logMessage("ERROR", "[USER-DELETE] Failed to fetch deleted users from server. HTTP=$httpCode err=$curlErr");
            }
            continue;
        }

        $data = json_decode($response, true);
        if (!$data || $data['status'] !== 'success') {
            continue;
        }

        $deletedUsers = $data['deleted_users'] ?? [];
        $total        = count($deletedUsers);

        if ($total === 0) {
            // لا يوجد موظفين محذوفين معلقين لهذا الجهاز
            continue;
        }

        logMessage("INFO", "[USER-DELETE] Found $total deleted employee(s) to remove from $name ($ip:$port)...");

        // 2. الاتصال بالماكينة لحذف المستخدمين
        $zk = new ZKTeco($ip, $port);
        if (!@$zk->connect()) {
            logMessage("ERROR", "[USER-DELETE] Cannot connect to device $name ($ip:$port) to remove users. Skipping.");
            continue;
        }

        $zk->disableDevice();

        $removedPins = [];
        $failedCount = 0;

        foreach ($deletedUsers as $item) {
            $uid     = (int)   ($item['uid']  ?? $item['pin'] ?? 0);
            $pin     = (string)($item['pin']  ?? $uid);
            $empName = (string)($item['name'] ?? '');

            if ($uid <= 0) {
                logMessage("WARNING", "[USER-DELETE] Skipping invalid UID: $uid ($empName)");
                continue;
            }

            // أمر حذف المستخدم وبصماته من جهاز ZKTeco
            $result = $zk->removeUser($uid);

            // نعتبر الحذف منجزاً سواء تم الحذف بنجاح أو كان الموظف غير موجود أصلاً على الماكينة
            $removedPins[] = $pin;
            if ($result !== false) {
                logMessage("INFO", "[USER-DELETE] ✔ Removed from device: PIN [$uid] $empName");
            } else {
                logMessage("INFO", "[USER-DELETE] User not present on device (or already cleared): PIN [$uid] $empName");
            }
        }

        $zk->enableDevice();
        $zk->disconnect();

        logMessage("INFO", "[USER-DELETE] Done for $name: " . count($removedPins) . " removed, $failedCount failed.");

        // 3. إبلاغ السيرفر بنجاح الحذف من الماكينة لتحديث الحالة
        if (!empty($removedPins)) {
            $confirmPayload = json_encode([
                'device_name'  => $name,
                'device_ip'    => $ip,
                'deleted_pins' => $removedPins,
            ]);
            $confirmUrl     = "{$serverUrl}/api/zkt/users/deleted/confirm";
            $res = sendHttpRequest($confirmUrl, $confirmPayload, $verifySsl, $isDebug);
            if ($res['success']) {
                logMessage("INFO", "[USER-DELETE] Server confirmed deletion for " . count($removedPins) . " employee(s) on device $name.");
            } else {
                logMessage("WARNING", "[USER-DELETE] Failed to confirm deletion with server: " . $res['error']);
            }
        }
    }
}

/**
 * Send HTTP POST Request with JSON payload
 */
function sendHttpRequest($url, $jsonPayload, $verifySsl = false, $isDebug = false) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonPayload);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Accept: application/json'
    ]);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $verifySsl);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, $verifySsl ? 2 : 0);

    if ($isDebug) {
        curl_setopt($ch, CURLOPT_VERBOSE, true);
    }

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curlError = curl_error($ch);
    curl_close($ch);

    if ($response === false) {
        return [
            'success'   => false,
            'http_code' => $httpCode,
            'error'     => $curlError,
            'body'      => null
        ];
    }

    if ($httpCode >= 200 && $httpCode < 300) {
        return [
            'success'   => true,
            'http_code' => $httpCode,
            'error'     => null,
            'body'      => $response
        ];
    }

    return [
        'success'   => false,
        'http_code' => $httpCode,
        'error'     => "HTTP Code $httpCode. Response: " . trim($response),
        'body'      => $response
    ];
}

/**
 * Log message helper
 */
function logMessage($level, $message) {
    $timestamp = date('Y-m-d H:i:s');
    $logLine = "[$timestamp] [$level] $message\n";
    
    // Print to CLI console
    echo $logLine;
    
    // Save to file
    file_put_contents(__DIR__ . '/sync_log.txt', $logLine, FILE_APPEND);
}
