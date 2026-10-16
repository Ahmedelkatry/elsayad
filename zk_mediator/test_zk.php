<?php
/**
 * ZK Mediator - Direct IP Diagnostic Health Check Tool
 * 
 * يفحص:
 * 1. الاتصال بالسيرفر الرئيسي للمشروع (فحص الـ API: /api/zkt/ping).
 * 2. الاتصال المباشر بماكينات البصمة عبر الـ IP والمنفذ 4370 محلياً.
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "========================================================\n";
echo "       ZK IP Mediator - Branch Diagnostic Health Check  \n";
echo "========================================================\n\n";

define('CONFIG_FILE', __DIR__ . '/config.json');

if (!file_exists(CONFIG_FILE)) {
    echo "[FAIL] config.json was not found in: " . __DIR__ . "\n";
    echo "       Please create config.json from the example.\n";
    exit(1);
}

$config = json_decode(file_get_contents(CONFIG_FILE), true);
if (!$config || !isset($config['server_url']) || !isset($config['devices'])) {
    echo "[FAIL] Invalid config.json structure! Must include 'server_url' and 'devices'.\n";
    exit(1);
}

// Custom Autoloader for ZKTeco Lib
spl_autoload_register(function ($class) {
    $prefix = 'Rats\\Zkteco\\';
    $base_dir = __DIR__ . '/lib/';
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }
    $file = $base_dir . str_replace('\\', '/', substr($class, $len)) . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
});

use Rats\Zkteco\Lib\ZKTeco;

$serverUrl = rtrim($config['server_url'], '/');
$serverUrl = preg_replace('#/(cdata/)?iclock(/cdata|/getrequest|/devicecmd)?$#i', '', $serverUrl);
$serverUrl = preg_replace('#/api/zkt/(upload|ping)$#i', '', $serverUrl);
$verifySsl = isset($config['verify_ssl']) ? (bool)$config['verify_ssl'] : false;

// ── 1. Test Server Connectivity ───────────────────────────────────
echo "[1/2] Testing Connection to Main Project Server...\n";
echo "      Server URL: $serverUrl\n";

$pingEndpoint = "{$serverUrl}/api/zkt/ping";
$ch = curl_init($pingEndpoint);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $verifySsl);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, $verifySsl ? 2 : 0);

$res = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curlErr = curl_error($ch);
curl_close($ch);

if ($res === false) {
    echo "      [FAIL] Cannot reach Project Server: $curlErr\n";
    echo "      -> Check server URL in config.json and internet connection on this PC.\n\n";
} else {
    $parsed = json_decode($res, true);
    if ($httpCode === 200 && isset($parsed['status']) && $parsed['status'] === 'online') {
        echo "      [PASS] Project Server API is ONLINE and Ready!\n";
        echo "             Server Time: " . ($parsed['server_time'] ?? 'N/A') . "\n\n";
    } else {
        echo "      [WARNING] Server responded (HTTP $httpCode) but unexpected response: " . trim($res) . "\n\n";
    }
}

// ── 2. Test Each Fingerprint Device via IP ─────────────────────────
echo "[2/2] Testing Direct IP Connection to Local Fingerprint Machines...\n";
$devices = $config['devices'];

if (empty($devices)) {
    echo "      [WARNING] No devices listed in config.json.\n\n";
}

$allPass = true;

foreach ($devices as $idx => $dev) {
    $num = $idx + 1;
    $ip = trim($dev['ip'] ?? '');
    $port = (int)($dev['port'] ?? 4370);
    $name = trim($dev['name'] ?? "Device #$num");

    echo "  --> Device $num: $name (IP: $ip:$port)\n";

    if (empty($ip)) {
        echo "      [FAIL] Missing IP address in config.json.\n\n";
        $allPass = false;
        continue;
    }

    $zk = new ZKTeco($ip, $port);
    if (!@$zk->connect()) {
        echo "      [FAIL] Could not connect to device at $ip:$port\n";
        echo "             -> Verify machine IP, Ethernet cable, and power.\n\n";
        $allPass = false;
        continue;
    }

    echo "      [PASS] Connected successfully via IP Socket!\n";

    // Retrieve device info
    try {
        $devName = $zk->deviceName();
        $devTime = $zk->getTime();
        $devVer = $zk->version();

        echo "      - Machine Model  : " . ($devName ?: 'N/A') . "\n";
        echo "      - Machine Clock  : " . ($devTime ?: 'N/A') . "\n";
        echo "      - Firmware Ver   : " . ($devVer ?: 'N/A') . "\n";

        $logs = $zk->getAttendance();
        $logCount = is_array($logs) ? count($logs) : 0;
        echo "      - Attendance Logs: $logCount records currently stored on device.\n";

    } catch (\Throwable $e) {
        echo "      [WARNING] Error reading device details: " . $e->getMessage() . "\n";
    }

    $zk->disconnect();
    echo "      Device $num check complete.\n\n";
}

echo "========================================================\n";
if ($allPass) {
    echo " Diagnostic Finished Successfully! You can now run zk.bat.\n";
} else {
    echo " Diagnostic Finished with some warnings/errors. Please review above.\n";
}
echo "========================================================\n";
