<?php

namespace App\Http\Controllers;

use App\Models\change_shift;
use App\Models\count;
use App\Models\eid;
use App\Models\employees;
use App\Models\forget_finger;
use App\Models\head_sections;
use App\Models\ipzkt;
use App\Models\nozkt;
use App\Models\please;
use App\Models\plzot;
use App\Models\shift;
use App\Models\shift_week;
use App\Models\SupShift;
use App\Models\vacation;
use App\Models\zkt;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class IpzktController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

       $zkts = ipzkt::all();
       return view("zkt_name.zkt_name", compact("zkts"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'            => 'required',
            'start'           => 'required',
            'connection_type' => 'required|in:ip,sn',
            'ip'              => 'required_if:connection_type,ip|nullable|string|max:50',
            'sn'              => 'required_if:connection_type,sn|nullable|string|max:50',
        ]);

        $data = $request->only(['name', 'ip', 'sn', 'start', 'end']);
        if ($request->connection_type === 'ip') {
            $data['sn'] = null;
        } else {
            // للسماح بإضافة IP Whitelist لأجهزة الـ ADMS (SN) لا نقوم بتفريغ الـ IP إذا تم إدخاله
            // $data['ip'] = null;
        }

        ipzkt::create($data);
        return back();
    }

    /**
     * Display the specified resource.
     */
    public function show(ipzkt $ipzkt)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ipzkt $ipzkt)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name'            => 'required',
            'start'           => 'required',
            'connection_type' => 'required|in:ip,sn',
            'ip'              => 'required_if:connection_type,ip|nullable|string|max:50',
            'sn'              => 'required_if:connection_type,sn|nullable|string|max:50',
        ]);

        $data = $request->only(['name', 'ip', 'sn', 'start', 'end']);
        if ($request->connection_type === 'ip') {
            $data['sn'] = null;
        } else {
            // للسماح بإضافة IP Whitelist لأجهزة الـ ADMS (SN) لا نقوم بتفريغ الـ IP إذا تم إدخاله
            // $data['ip'] = null;
        }

        $device = ipzkt::findOrFail($id);
        $device->update($data);

        session()->flash('edit', 'تم تعديل جهاز البصمة بنجاح');
        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $device = ipzkt::findOrFail($id);
        $device->delete();

        session()->flash('delete', 'تم حذف جهاز البصمة بنجاح');
        return back();
    }

    /**
     * مزامنة الموظفين المعلقين إلى جهاز بصمة محدد عبر الـ IP مباشرة
     * يُستدعى تلقائياً عند تشغيل الجهاز، أو فحص حالته، أو سحب البصمات، أو عبر الأمر zkt:sync
     */
    public static function syncPendingEmployeesToDevice($deviceOrIp)
    {
        try {
            $device = is_object($deviceOrIp)
                ? $deviceOrIp
                : ipzkt::where('ip', $deviceOrIp)->orWhere('name', $deviceOrIp)->orWhere('sn', $deviceOrIp)->first();

            $targetIp = ($device && $device->ip) ? $device->ip : (is_string($deviceOrIp) ? $deviceOrIp : null);

            if (!$targetIp || !filter_var($targetIp, FILTER_VALIDATE_IP)) {
                return 0;
            }

            // مفاتيح الجهاز لمطابقتها في جدول zkt_device_sync
            $deviceKeys = array_unique(array_filter([
                $targetIp,
                $device ? $device->name : null,
                $device ? $device->ip : null,
                $device ? $device->sn : null,
            ]));

            // استبعاد الموظفين الذين تمت مزامنتهم بالفعل لهذا الجهاز
            $alreadySynced = DB::table('zkt_device_sync')
                ->whereIn('device_key', $deviceKeys)
                ->pluck('emp_num')
                ->toArray();

            $query = employees::query()->whereNotNull('num');

            if ($device && $device->start > 0 && $device->end > 0) {
                $query->whereBetween('num', [$device->start, $device->end]);
            }

            if ($device) {
                $devIp   = $device->ip;
                $devSn   = $device->sn;
                $devName = $device->name;
                $query->where(function ($q) use ($devIp, $devSn, $devName) {
                    $q->whereNull('ipz')
                      ->orWhere('ipz', '')
                      ->orWhere('ipz', 'LIKE', "%{$devIp}%");
                    if ($devSn) {
                        $q->orWhere('ipz', 'LIKE', "%{$devSn}%");
                    }
                    if ($devName) {
                        $q->orWhere('ipz', 'LIKE', "%{$devName}%");
                    }
                });
            }

            if (!empty($alreadySynced)) {
                $query->whereNotIn('num', $alreadySynced);
            }

            $pendingEmps = $query->get();
            if ($pendingEmps->isEmpty()) {
                return 0; // لا يوجد موظفين معلقين
            }

            // فحص سريع للسوكيت أولاً (1.2 ثانية مهلة) للتأكد أن البصمة تعمل
            $errno = 0;
            $errstr = '';
            $testSock = @fsockopen($targetIp, 4370, $errno, $errstr, 1.2);
            if (!$testSock) {
                return 0; // الجهاز مغلق حالياً
            }
            fclose($testSock);

            // الاتصال بماكينة البصمة
            $zk = new \Rats\Zkteco\Lib\ZKTeco($targetIp, 4370);
            if (!@$zk->connect()) {
                return 0;
            }

            @$zk->disableDevice();
            $syncedCount = 0;

            foreach ($pendingEmps as $emp) {
                $uid = (int) $emp->num;
                if ($uid <= 0) {
                    continue;
                }

                $pass = ($emp->password === '' || $emp->password === null) ? null : $emp->password;
                $role = (int) ($emp->role_zkt ?? 0);
                if ($role !== 14) {
                    $role = 0;
                }

                $res = @$zk->setUser($uid, (string)$uid, $emp->name, $pass, $role);
                if ($res !== false) {
                    $syncedCount++;
                    foreach ($deviceKeys as $k) {
                        DB::table('zkt_device_sync')->updateOrInsert(
                            ['device_key' => $k, 'emp_num' => (string)$uid],
                            ['synced_at' => now(), 'updated_at' => now()]
                        );
                    }
                    $emp->update(['zkt_synced' => 1]);
                }
            }

            @$zk->enableDevice();
            @$zk->disconnect();

            if ($syncedCount > 0) {
                \Log::info("ZKTeco Auto-Sync: Automatically pushed {$syncedCount} employee(s) to device {$targetIp} (" . ($device ? $device->name : '') . ")");
            }

            return $syncedCount;

        } catch (\Throwable $e) {
            \Log::warning("ZKTeco Auto-Sync failed for {$deviceOrIp}: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Check if the fingerprint device is connected to the network.
     * Uses fast socket check (1.2 second timeout) and auto-syncs any pending employees.
     */
    public function check_status($ip)
    {
        try {
            // البحث عن الجهاز في قاعدة البيانات أولاً
            $device = ipzkt::where('ip', $ip)->orWhere('sn', $ip)->orWhere('name', $ip)->first();

            // تحديد الـ IP المستهدف للفحص
            $targetIp = ($device && $device->ip) ? $device->ip : $ip;

            // إذا كان الجهاز لديه IP → نفحص Socket أولاً
            if ($targetIp && filter_var($targetIp, FILTER_VALIDATE_IP)) {
                $errno = 0;
                $errstr = '';
                $socket = @fsockopen($targetIp, 4370, $errno, $errstr, 1.2);
                if ($socket) {
                    fclose($socket);

                    // الجهاز متصل وشغال الآن! نقوم بمزامنة أي موظفين معلقين فوراً
                    $syncedCount = self::syncPendingEmployeesToDevice($device ?: $targetIp);

                    return response()->json([
                        'status' => 'online',
                        'mode' => 'ip',
                        'synced_employees' => $syncedCount,
                    ]);
                }
            }

            // إذا كان الجهاز لديه نشاط حديث خلال آخر 5 دقائق (من وسيط الفروع zk_mediator أو ADMS)
            if ($device && $device->last_activity && \Carbon\Carbon::parse($device->last_activity)->gt(now()->subMinutes(5))) {
                return response()->json(['status' => 'online', 'mode' => 'mediator']);
            }

            return response()->json(['status' => 'offline']);

        } catch (\Exception $e) {
            \Log::error("check_status exception: " . $e->getMessage());
            return response()->json(['status' => 'offline']);
        }
    }

    /**
     * ADMS endpoint - استقبال بيانات البصمة من أجهزة ZKT عبر بروتوكول ADMS
     * يجب إضافة هذا الـ URL في إعدادات الجهاز: http://YOUR_SERVER/adms
     * الجهاز يرسل: SN (Serial Number) + بيانات الحضور
     *
     * Route: GET/POST /adms
     */
  public function adms(\Illuminate\Http\Request $request)
{
    // جلب الـ Tenant الحالي من الحزمة
    $tenant = config('app.name', 'app'); 

    // 1. جلب الرقم التسلسلي (SN) من الهيدر أو الـ query string
    $sn = $request->header('SN') ?? $request->query('SN') ?? $request->input('SN');

    if (!$sn) {
        \Log::info("ADMS [$tenant]: No SN provided. Replying with OK.");
        return response("OK\n", 200)->header('Content-Type', 'text/plain');
    }

    // 2. التحقق من أن الجهاز مسجّل في جدول ipzkts (عبر SN أو IP أو الاسم)
    $device = \App\Models\ipzkt::where('sn', $sn)
        ->orWhere('ip', $sn)
        ->orWhere('name', $sn)
        ->first();

    if (!$device) {
        \Log::warning("ADMS [$tenant]: Unknown device SN/Identifier: $sn. Incoming IP: " . $request->ip());
        return response("OK\n", 200)->header('Content-Type', 'text/plain');
    }

    // 3. التحقق من أمان الاتصال (IP Whitelisting)
    // نطبقه فقط إذا كان عنوان الـ IP المسجل هو IP عام (Public IP)، وليس عنوان شبكة محلية (LAN IP مثل 192.168.x.x)
    if (!empty($device->ip)) {
        $isPrivateOrReserved = filter_var($device->ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) === false;
        if (!$isPrivateOrReserved && $request->ip() !== $device->ip) {
            \Log::warning("ADMS Security Alert [$tenant]: Unauthorized IP ({$request->ip()}) attempting to send data for SN: $sn. Expected IP: {$device->ip}");
            return response("OK\n", 200)->header('Content-Type', 'text/plain');
        }
    }

    // تحديث تاريخ ووقت آخر ظهور (Heartbeat) للجهاز
    $device->update(['last_activity' => now()]);

    $path    = $request->path();
    $rawBody = $request->getContent();
    $table   = $request->input('table', '');
    $method  = $request->method();

    // -----------------------------------------------------------------
    // 1. مسار سحب الأوامر (getrequest): الجهاز بيسأل هل في موظفين جدد؟
    // -----------------------------------------------------------------
    if (str_contains($path, 'getrequest')) {
        // بنجيب أول أمر لسه متنفذش للجهاز ده
        $pendingCmd = \App\Models\ZktCommand::where('sn', $sn)->where('executed', 0)->first();
        
        if ($pendingCmd) {
            // بروتوكول الجهاز بيشترط الصيغة دي للأوامر: C:رقم_الأمر:الأمر
            $commandString = "C:{$pendingCmd->id}:{$pendingCmd->command}\n";
            
            // نعلمه إنه تم عشان ميتبعتش مرتين
            $pendingCmd->update(['executed' => 1]);
            
            \Log::info("ADMS SEND CMD [$tenant]: $commandString");
            return response($commandString, 200)->header('Content-Type', 'text/plain');
        }
        
        // لو مفيش أوامر، لازم نرد بـ OK
        return response("OK\n", 200)->header('Content-Type', 'text/plain');
    }

    // -----------------------------------------------------------------
    // 2. مسار تأكيد الأوامر (devicecmd): الجهاز بيبلغنا إنه نفذ الأمر
    // -----------------------------------------------------------------
    if (str_contains($path, 'devicecmd')) {
        return response("OK\n", 200)->header('Content-Type', 'text/plain');
    }

    // -----------------------------------------------------------------
    // حالة الـ POST: الجهاز يقوم بإرسال بيانات الحضور الفعلية لحفظها
    // -----------------------------------------------------------------
    if ($method === 'POST') {
        \Log::info("ADMS POST [$tenant]: Device SN=$sn (" . $device->name . ") | Table=$table");

        // معالجة بيانات الحضور (ATTLOG) عند إرسال البصمات
        if ($table === 'ATTLOG' || str_contains($rawBody, 'table=ATTLOG') || str_contains($request->getQueryString(), 'table=ATTLOG')) {
            $this->_processAdmsAttlog($rawBody, $device);
        }

        // الرد الإجباري على طلبات الـ POST هو OK فقط ليعتمد الجهاز إرسال البيانات
        return response("OK\n", 200)->header('Content-Type', 'text/plain');
    }

    // -----------------------------------------------------------------
    // حالة الـ GET: الجهاز يطلب الإعدادات أول مرة أو يقوم بعمل Heartbeat
    // -----------------------------------------------------------------
    \Log::info("ADMS GET [$tenant]: Device SN=$sn (" . $device->name . ") | Table=$table");

    $serverTime = now()->format('Y-m-d H:i:s');
    $response   = "GET OPTION\r\n";
    $response  .= "Realtime=1\r\n";
    $response  .= "ServerVer=2.4.1 2015-04-14\r\n";
    $response  .= "PushProtVer=2.4.1\r\n";
    $response  .= "ErrorDelay=30\r\n";
    $response  .= "Delay=30\r\n";
    $response  .= "TransTimes=00:00;14:05\r\n";
    $response  .= "TransInterval=1\r\n";
    $response  .= "TransFlag=TransData AttLog User\r\n";
    $response  .= "TimeZone=03:00\r\n";
    $response  .= "Encrypt=None\r\n";
    $serverTime = now()->format('Y-m-d H:i:s');
    $response  .= "ServerTime=$serverTime\r\n";

    return response($response, 200)->header('Content-Type', 'text/plain');
}

/**
 * معالجة سجلات الحضور المرسلة من الجهاز (ATTLOG) وتخزينها في جدول zkt ثم حساب الـ counts
 *
 * المنطق الكامل:
 * 1. قاعدة الـ 5 صباحاً → البصمات قبل 5 صباحاً تتبع اليوم السابق
 * 2. أول بصمة دخول (status=0) وآخر بصمة خروج (status=1)
 * 3. دعم الاستراحة (status=3 دخول، status=4 خروج)
 * 4. معالجة الشفت المتداخل (Overnight Shift)
 * 5. حساب counts كامل (تأخير، إضافي، إجازات، استئذانات)
 */
private function _processAdmsAttlog(string $rawBody, ipzkt $device): void
{
    $lines = explode("\n", $rawBody);

    // ─── المرحلة 1: تجميع البصمات من ATTLOG ───────────────────────────────────
    // نجمع كل البصمات الواردة في هذه الدفعة في مصفوفة مؤقتة
    $batchData = []; // [pin][date] => ['punches'=>[], 'b_in'=>, 'b_out'=>]

    foreach ($lines as $line) {
        $line = trim($line);
        if (empty($line) || str_starts_with($line, 'table=') || str_starts_with($line, 'Stamp=')) {
            continue;
        }

        // تفكيك: PIN \t DateTime \t Status \t ...
        $parts = preg_split('/\t+/', $line);
        if (count($parts) < 3) {
            continue;
        }

        $pin      = trim($parts[0]);
        $dateTime = trim($parts[1]);
        $status   = (int) trim($parts[2] ?? '0'); // 0=دخول، 1=خروج، 3=استراحة دخول، 4=استراحة خروج

        try {
            // التحقق من نطاق الجهاز
            $pinInt = (int) $pin;
            if ($device->end > 0 && ($pinInt < $device->start || $pinInt > $device->end)) {
                Log::warning("ADMS: PIN $pin out of range [{$device->start}-{$device->end}] device={$device->name}");
                continue;
            }

            $ts = Carbon::parse($dateTime);
            $emp = employees::where('num', $pin)->first();
            $date = $this->_resolvePunchDate($emp, $ts);
            $timeStr = $ts->format('H:i:s'); // الوقت الفعلي للبصمة

            if (!isset($batchData[$pin][$date])) {
                $batchData[$pin][$date] = [
                    'punches' => [],
                    'b_in'    => null,
                    'b_out'   => null,
                ];
            }

            $cur = &$batchData[$pin][$date];

            if ($status == 0 || $status == 1) {
                $cur['punches'][] = $timeStr;
            } elseif ($status == 3) { // استراحة دخول
                $cur['b_in'] = $timeStr;
            } elseif ($status == 4) { // استراحة خروج
                $cur['b_out'] = $timeStr;
            }
        } catch (\Exception $e) {
            Log::error("ADMS parse error line='$line': " . $e->getMessage());
        }
    }

        if (!empty($batchData)) {
            $this->_saveBatchAndCalculateCounts($batchData);
        }
    }

    /**
     * نقطة اتصال وسيط البصمة للفروع (IP Mediator API)
     * يستقبل بيانات الحضور المسحوبة محلياً عبر IP من كل فرع بصيغة JSON ويحفظها في النظام
     * Route: POST /api/zkt/upload
     */
    public function uploadFromMediator(\Illuminate\Http\Request $request)
    {
        $deviceIp = $request->input('ip');
        $deviceName = $request->input('name');
        $logs = $request->input('logs', []);

        // البحث عن الجهاز المسجل في جدول ipzkts
        $device = null;
        if (!empty($deviceIp)) {
            $device = ipzkt::where('ip', $deviceIp)->orWhere('name', $deviceIp)->orWhere('sn', $deviceIp)->first();
        }
        if (!$device && !empty($deviceName)) {
            $device = ipzkt::where('name', $deviceName)->orWhere('ip', $deviceName)->orWhere('sn', $deviceName)->first();
        }

        if ($device) {
            $device->update(['last_activity' => now()]);
        }

        // إذا كان الطلب مجرد فحص حالة (Heartbeat) بدون بصمات
        if (empty($logs) || !is_array($logs)) {
            return response()->json([
                'status' => 'success',
                'message' => 'Heartbeat received successfully',
                'device' => $device ? $device->name : null,
                'synced_count' => 0
            ]);
        }

        $batchData = [];
        $totalLogsProcessed = 0;

        foreach ($logs as $log) {
            $pin = trim($log['id'] ?? $log['pin'] ?? '');
            $dateTime = trim($log['timestamp'] ?? '');
            $status = (int) ($log['type'] ?? 0); // 0=دخول، 1=خروج، 3=استراحة دخول، 4=استراحة خروج

            if (empty($pin) || empty($dateTime)) {
                continue;
            }

            // فحص نطاق الموظفين المسموح للجهاز إذا كان محدداً
            $pinInt = (int) $pin;
            if ($device && $device->end > 0 && ($pinInt < $device->start || $pinInt > $device->end)) {
                continue;
            }

            try {
                $ts = Carbon::parse($dateTime);
                $emp = employees::where('num', $pin)->first();
                $date = $this->_resolvePunchDate($emp, $ts);
                $timeStr = $ts->format('H:i:s');

                if (!isset($batchData[$pin][$date])) {
                    $batchData[$pin][$date] = [
                        'punches' => [],
                        'b_in'    => null,
                        'b_out'   => null,
                    ];
                }

                $cur = &$batchData[$pin][$date];
                if ($status == 0 || $status == 1) {
                    $cur['punches'][] = $timeStr;
                } elseif ($status == 3) {
                    $cur['b_in'] = $timeStr;
                } elseif ($status == 4) {
                    $cur['b_out'] = $timeStr;
                }
                $totalLogsProcessed++;
            } catch (\Exception $e) {
                Log::error("Mediator parse error: " . $e->getMessage());
            }
        }

        if (!empty($batchData)) {
            $this->_saveBatchAndCalculateCounts($batchData);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Attendance logs processed successfully',
            'device' => $device ? $device->name : ($deviceName ?: $deviceIp),
            'synced_count' => $totalLogsProcessed
        ]);
    }

    /**
     * فحص اتصال وسيط البصمة (Ping)
     * Route: GET /api/zkt/ping
     */
    public function pingMediator(\Illuminate\Http\Request $request)
    {
        return response()->json([
            'status' => 'online',
            'message' => 'ZK Mediator IP Attendance API is running',
            'server_time' => now()->format('Y-m-d H:i:s')
        ]);
    }

    /**
     * حفظ وتحديث جدول zkts وحساب الحضور والغياب والإضافي في جدول counts
     */
    private function _saveBatchAndCalculateCounts(array $batchData): void
    {
        // ─── المرحلة 2: تحديث جدول zkts ──────────────────────────────────────────
        $affectedDates = [];
        $affectedPins  = array_keys($batchData);

    DB::beginTransaction();
    try {
        foreach ($batchData as $pin => $dates) {
            $emp = employees::where('num', $pin)->first();

            foreach ($dates as $date => $data) {
                $affectedDates[] = $date;

                $zktRecord = zkt::firstOrNew(['numid' => $pin, 'date' => $date]);

                if (empty($zktRecord->name)) {
                    $zktRecord->name = $emp ? $emp->name : $pin;
                }

                // تحديد وقت بداية الشفت لترتيب البصمات بشكل صحيح
                $empShift = $emp ? $this->_getEmployeeShiftOnDate($emp, $date) : null;
                $shiftStartTime = $empShift ? $empShift->from : '00:00:00';

                // Combine existing DB punches and new punches
                $allPunches = [];
                if ($zktRecord->in) $allPunches[] = $zktRecord->in;
                if ($zktRecord->out) $allPunches[] = $zktRecord->out;

                if (!empty($data['punches'])) {
                    $allPunches = array_merge($allPunches, $data['punches']);
                }

                $allPunches = array_unique($allPunches);

                if (count($allPunches) > 0) {
                    $shiftRef = $shiftStartTime;
                    usort($allPunches, function($a, $b) use ($shiftRef) {
                        return $this->_compareTimes($a, $b, $shiftRef);
                    });
                    $zktRecord->in = $allPunches[0];
                    if (count($allPunches) > 1) {
                        $zktRecord->out = end($allPunches);
                    } else {
                        $zktRecord->out = null;
                    }
                }

                if ($data['b_in'])  $zktRecord->break_in  = $data['b_in'];
                if ($data['b_out']) $zktRecord->break_out = $data['b_out'];

                $zktRecord->absent = ($zktRecord->in && $zktRecord->out)
                    ? 'حاضر'
                    : ($zktRecord->in ? 'دخول فقط' : 'غائب');

                $zktRecord->save();

                Log::info("ADMS: zkt saved PIN=$pin Date=$date in={$zktRecord->in} out={$zktRecord->out}");
            }
        }
        DB::commit();
    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('ADMS: zkt save error: ' . $e->getMessage());
        return;
    }

    // ─── المرحلة 3: تحديث الحالة بعد الحفظ ───────────────────────────────────
    $affectedDates = array_unique($affectedDates);
    $affectedEmps  = employees::with(['shift'])->whereIn('num', $affectedPins)->get();

    // ─── المرحلة 4: حساب جدول counts كامل ───────────────────────────────────
    // نحمّل البيانات المساعدة مرة واحدة فقط
    $vacations     = vacation::all()->groupBy('employees_id');
    $permissions   = please::all()->groupBy('employees_id');
    $forgetFingers = forget_finger::all()->groupBy('employees_id');
    $nozktData     = nozkt::all()->groupBy('numid');
    $eids          = eid::all()->pluck('date')->toArray();
    $supShifts     = SupShift::all();
    $changeShifts  = change_shift::all()->groupBy('employees_id');
    $headSections  = head_sections::all()->groupBy('section_id');
    $shiftWeeks    = shift_week::all()->groupBy('employees_id');

    // السجلات الحالية في counts لتسريع العملية
    $existingCounts = count::whereIn('date', $affectedDates)
        ->whereIn('numid', $affectedPins)
        ->get()
        ->groupBy(fn($c) => $c->employees_id . '_' . $c->date);

    // كل سجلات zkts المتأثرة
    $zktRecords = zkt::whereIn('date', $affectedDates)
        ->whereIn('numid', $affectedPins)
        ->get();

    DB::beginTransaction();
    try {
        foreach ($zktRecords as $zktRecord) {
            $pin  = $zktRecord->numid;
            $date = $zktRecord->date;

            $emp = $affectedEmps->where('num', $pin)->first();
            if (!$emp) continue;

            // ── أ. دمج نسيان البصمة و nozkt ──
            $checkIn  = $zktRecord->in;
            $checkOut = $zktRecord->out;

            $nz = isset($nozktData[$pin])
                ? $nozktData[$pin]->where('date', $date)->first()
                : null;
            if ($nz) {
                if (!$checkIn  && $nz->in)  $checkIn  = $nz->in;
                if (!$checkOut && $nz->out) $checkOut = $nz->out;
            }

            $ff = isset($forgetFingers[$emp->id])
                ? $forgetFingers[$emp->id]->where('date', $date)->first()
                : null;
            if ($ff) {
                if (!$checkIn)  $checkIn  = $ff->in;
                if (!$checkOut) $checkOut = $ff->out;
            }

            // ── ب. تحديد الشفت الفعلي (قسم / فردي / أسبوعي / استثنائي) ──
            $actualShiftId = $emp->shift_id;
            $actualShift   = $emp->shift;

            $hs = isset($headSections[$emp->section_id])
                ? $headSections[$emp->section_id]
                    ->where('date_start', '<=', $date)
                    ->where('date_end', '>=', $date)
                    ->first()
                : null;
            if ($hs) {
                $actualShiftId = $hs->shift_id;
                $actualShift   = shift::find($actualShiftId);
            }

            $cs = isset($changeShifts[$emp->id])
                ? $changeShifts[$emp->id]
                    ->where('date_start', '<=', $date)
                    ->where('date_end', '>=', $date)
                    ->first()
                : null;
            if ($cs) {
                $actualShiftId = $cs->shift_id;
                $actualShift   = shift::find($actualShiftId);
            }

            $sw = isset($shiftWeeks[$emp->id])
                ? $shiftWeeks[$emp->id]->where('date', $date)->first()
                : null;
            if ($sw) {
                $actualShiftId = $sw->shift_id;
                $actualShift   = shift::find($actualShiftId);
            }

            $ss = $supShifts
                ->where('shift_id', $actualShiftId)
                ->where('start_date', '<=', $date)
                ->where('end_date', '>=', $date)
                ->first();
            if ($ss) {
                $actualShiftId = $ss->override_shift_id;
                $actualShift   = shift::find($actualShiftId);
            }

            if (!$actualShift) continue;

            // ── ج. حساب قيمة اليوم والساعة ──
            $monthStart      = Carbon::parse($date)->startOfMonth();
            $monthEnd        = Carbon::parse($date)->endOfMonth();
            $workingDaysCount = 30;

            if ($emp->choosesys == 1) {
                $workingDaysCount = 30;
            } elseif ($emp->choosesys == 2) {
                $holidaysCount = 0;
                if ($emp->check == 1 && is_array($emp->free_d)) {
                    for ($d = clone $monthStart; $d <= $monthEnd; $d->addDay()) {
                        if (in_array($d->locale('ar')->dayName, $emp->free_d)) {
                            $holidaysCount++;
                        }
                    }
                }
                $workingDaysCount = $monthStart->daysInMonth - $holidaysCount;
            } elseif ($emp->choosesys == 3) {
                $workingDaysCount = $emp->sysemp > 0 ? $emp->sysemp : 30;
            }

            $dayValue  = $workingDaysCount > 0 ? ($emp->kabd / $workingDaysCount) : 0;
            $hourValue = $actualShift->workh > 0 ? ($dayValue / $actualShift->workh) : 0;

            // ── د. إنشاء / تحديث سجل count ──
            $countKey = $emp->id . '_' . $date;
            $countRec = isset($existingCounts[$countKey])
                ? $existingCounts[$countKey]->first()
                : new count;

            $countRec->numid       = $pin;
            $countRec->employees_id = $emp->id;
            $countRec->name        = $emp->name;
            $countRec->date        = $date;
            $countRec->in          = $checkIn;
            $countRec->out         = $checkOut;
            $countRec->break_in    = $zktRecord->break_in;
            $countRec->break_out   = $zktRecord->break_out;
            $countRec->shift_id    = $actualShiftId;
            $countRec->section_id  = $emp->section_id;
            $countRec->in_duty     = $actualShift->from;
            $countRec->out_duty    = $actualShift->to;
            $countRec->kabdd       = $emp->kabd;

            // إعادة تصفير الحسابات قبل إعادة الحساب
            $countRec->dis     = 0;
            $countRec->disout  = 0;
            $countRec->early   = null;
            $countRec->ot      = null;
            $countRec->othours = 0;
            $countRec->dayh    = 0;

            // ── هـ. حساب إجمالي ساعات العمل ──
            $timeIn  = $checkIn  ? Carbon::parse($date . ' ' . $checkIn)  : null;
            $timeOut = $checkOut ? Carbon::parse($date . ' ' . $checkOut) : null;

            // في حالة الوردية المتداخلة عبر منتصف الليل
            $isOvernightShift = false;
            if ($actualShift->from && $actualShift->to) {
                $isOvernightShift = Carbon::parse($actualShift->to)->lt(Carbon::parse($actualShift->from));
            }

            if ($isOvernightShift) {
                $shiftFromTime = Carbon::parse($actualShift->from);
                $logicalCutoff = $shiftFromTime->copy()->subHours(4)->format('H:i:s');

                if ($timeIn && Carbon::parse($checkIn)->format('H:i:s') < $logicalCutoff) {
                    $timeIn->addDay();
                }
                if ($timeOut && Carbon::parse($checkOut)->format('H:i:s') < $logicalCutoff) {
                    $timeOut->addDay();
                }
            }

            if ($timeIn && $timeOut) {
                if ($timeOut->lt($timeIn)) $timeOut->addDay(); // وردية متداخلة
                $countRec->dayh = $timeIn->diffInSeconds($timeOut);
            }

            $actualLateMinutes  = 0;
            $actualEarlyMinutes = 0;

            // ── و. حساب التأخير والإضافي بحسب نوع الوردية ──
            if ($actualShift->shift_time === 0) {
                // وردية بالساعات
                $requiredMinutes = $actualShift->workh * 60;
                $workedMinutes   = $countRec->dayh / 60;
                $missingMinutes  = $requiredMinutes - $workedMinutes;

                if ($missingMinutes > 0) {
                    $actualLateMinutes = $missingMinutes;
                    $countRec->early   = Carbon::parse('00:00:00')->addMinutes($missingMinutes);

                    if ($actualShift->late3 > 0 && $missingMinutes >= $actualShift->late3) {
                        $countRec->dis = round($actualShift->dism3 * ($hourValue / 60), 2);
                    } elseif ($actualShift->late2 > 0 && $missingMinutes >= $actualShift->late2) {
                        $countRec->dis = round($actualShift->dism2 * ($hourValue / 60), 2);
                    } elseif ($actualShift->late1 > 0 && $missingMinutes >= $actualShift->late1) {
                        $countRec->dis = round($actualShift->dism1 * ($hourValue / 60), 2);
                    } elseif ($actualShift->late > 0 && $missingMinutes >= $actualShift->late) {
                        $countRec->dis = round($actualShift->dism * ($hourValue / 60), 2);
                    } else {
                        $countRec->dis = 0;
                    }
                }

                if ($workedMinutes > $requiredMinutes) {
                    $countRec->othours = ($workedMinutes - $requiredMinutes) * 60;
                    $countRec->ot      = $timeOut?->format('H:i:s');
                }
            } else {
                // وردية ثابتة
                $shiftFrom = Carbon::parse($date . ' ' . $actualShift->from);
                $shiftTo   = Carbon::parse($date . ' ' . $actualShift->to);
                $startOt   = Carbon::parse($date . ' ' . $actualShift->start_ot);

                if ($shiftTo->lt($shiftFrom))  $shiftTo->addDay();
                if ($startOt->lt($shiftFrom))  $startOt->addDay();

                // التأخير
                if ($timeIn && $timeIn->gt($shiftFrom->copy()->addMinutes($actualShift->late))) {
                    $lateMinutes       = $shiftFrom->diffInMinutes($timeIn);
                    $actualLateMinutes = $lateMinutes;
                    $countRec->early   = Carbon::parse('00:00:00')->addMinutes($lateMinutes);

                    if ($actualShift->late3 > 0 && $lateMinutes >= $actualShift->late3) {
                        $countRec->dis = round($actualShift->dism3 * ($hourValue / 60), 2);
                    } elseif ($actualShift->late2 > 0 && $lateMinutes >= $actualShift->late2) {
                        $countRec->dis = round($actualShift->dism2 * ($hourValue / 60), 2);
                    } elseif ($actualShift->late1 > 0 && $lateMinutes >= $actualShift->late1) {
                        $countRec->dis = round($actualShift->dism1 * ($hourValue / 60), 2);
                    } else {
                        $countRec->dis = round($actualShift->dism * ($hourValue / 60), 2);
                    }
                }

                // الخروج المبكر
                if ($timeOut && $timeOut->lt($shiftTo->copy()->subMinutes($actualShift->bdry))) {
                    $earlyMinutes        = $timeOut->diffInMinutes($shiftTo);
                    $actualEarlyMinutes  = $earlyMinutes;
                    $countRec->disout    = round($actualShift->disout * ($hourValue / 60) * $earlyMinutes, 2);
                }

                // الإضافي
                if ($timeOut && $timeOut->gt($startOt)) {
                    $countRec->othours = $startOt->diffInSeconds($timeOut);
                    $countRec->ot      = $timeOut->format('H:i:s');
                }
            }

            // ── ز. طلبات الإضافي ──
            if ($countRec->othours > 0) {
                plzot::firstOrCreate(
                    ['numid' => $pin, 'date' => $date],
                    [
                        'name'     => $emp->name,
                        'in_duty'  => $actualShift->from,
                        'out_duty' => $actualShift->to,
                        'in'       => $checkIn,
                        'out'      => $checkOut,
                        'othours'  => $countRec->othours,
                        'count_ot' => round((($actualShift->ot * $countRec->othours) / 3600) * $hourValue, 2),
                        'status'   => 0,
                    ]
                );
            }

            // الإضافي المعتمد
            $approvedOt = plzot::where('numid', $pin)->where('date', $date)->where('status', 1)->first();
            if ($approvedOt) {
                $countRec->count_ot = round((($actualShift->ot * $countRec->othours) / 3600) * $hourValue, 2);
            }

            // ── ح. الغياب والإجازات ──
            $vac = isset($vacations[$emp->id])
                ? $vacations[$emp->id]->where('date', $date)->first()
                : null;

            if ($vac) {
                $countRec->absent     = $vac->disvac > 0 ? 1 : 2;
                $countRec->absent_dis = round($dayValue * $vac->disvac, 2);
                $countRec->dis        = 0;
                $countRec->disout     = 0;
            } elseif (!$checkIn && !$checkOut) {
                $countRec->absent     = 1;
                $countRec->absent_dis = round($dayValue * $actualShift->absent, 2);
            } else {
                $countRec->absent = 2;
            }

            // ── ط. الاستئذانات ──
            $plz = isset($permissions[$emp->id])
                ? $permissions[$emp->id]->where('date', $date)->first()
                : null;

            if ($plz) {
                if ($plz->in_out == 0) { // إذن دخول
                    if ($actualLateMinutes > $plz->minutes) {
                        if ($plz->dis_value > 0) $countRec->dis = $plz->dis_value;
                        if ($plz->dis > 0)       $countRec->dis = $plz->dis * ($actualLateMinutes * ($hourValue / 60));
                    } else {
                        $countRec->dis = 0;
                    }
                } else { // إذن خروج
                    if ($actualEarlyMinutes > $plz->minutes) {
                        if ($plz->dis_value > 0) $countRec->disout = $plz->dis_value;
                        if ($plz->dis > 0)       $countRec->disout = $plz->dis * ($actualEarlyMinutes * ($hourValue / 60));
                    } else {
                        $countRec->disout = 0;
                    }
                }
            }

            // إذا لم يدخل أو لم يخرج → لا تأخير
            if (!$timeIn || !$timeOut) {
                $countRec->dis   = 0;
                $countRec->early = 0;
            }

            // ── ي. الإجازات الرسمية والعطل الأسبوعية ──
            $isHoliday = in_array($date, $eids);
            $dayName   = Carbon::parse($date)->locale('ar')->dayName;
            $isWeekend = is_array($emp->free_d) && in_array($dayName, $emp->free_d);

            if ($isHoliday || $isWeekend) {
                $countRec->absent     = 0;
                $countRec->absent_dis = 0;
                if ($isHoliday) $countRec->eid = 1;
                $countRec->friday = $timeIn ? 1 : null;
            }

            $countRec->save();

            Log::info("ADMS: count saved PIN=$pin Date=$date absent={$countRec->absent} dis={$countRec->dis}");
        }
        DB::commit();
    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('ADMS: counts calc error: ' . $e->getMessage());
    }
}

    /**
     * Resolve the active shift for an employee on a given date.
     */
    private function _getEmployeeShiftOnDate($emp, $date)
    {
        if (!$emp) return null;

        $actualShiftId = $emp->shift_id;

        // 1. Head sections override
        $hs = \App\Models\head_sections::where('section_id', $emp->section_id)
            ->where('date_start', '<=', $date)
            ->where('date_end', '>=', $date)
            ->first();
        if ($hs) {
            $actualShiftId = $hs->shift_id;
        }

        // 2. Individual change shift override
        $cs = \App\Models\change_shift::where('employees_id', $emp->id)
            ->where('date_start', '<=', $date)
            ->where('date_end', '>=', $date)
            ->first();
        if ($cs) {
            $actualShiftId = $cs->shift_id;
        }

        // 3. Weekly shift override
        $sw = \App\Models\shift_week::where('employees_id', $emp->id)
            ->where('date', $date)
            ->first();
        if ($sw) {
            $actualShiftId = $sw->shift_id;
        }

        // 4. Override Shift (SupShift)
        $ss = \App\Models\SupShift::where('shift_id', $actualShiftId)
            ->where('start_date', '<=', $date)
            ->where('end_date', '>=', $date)
            ->first();
        if ($ss) {
            $actualShiftId = $ss->override_shift_id;
        }

        return \App\Models\shift::find($actualShiftId);
    }

    /**
     * Resolve the punch date based on the employee's previous shift and the 7-hour overtime rule.
     * Supports both normal and overnight (overlapping) shifts.
     */
    private function _resolvePunchDate($emp, \Carbon\Carbon $dateTime)
    {
        $dateStr = $dateTime->format('Y-m-d');
        if (!$emp) {
            return $dateStr;
        }

        // Check if this punch belongs to the previous day's shift window (including 7 hours overtime)
        $prevDate = $dateTime->copy()->subDay()->format('Y-m-d');
        $prevShift = $this->_getEmployeeShiftOnDate($emp, $prevDate);

        if ($prevShift && !empty($prevShift->from) && !empty($prevShift->to)) {
            $shiftFrom = $prevShift->from;
            $shiftTo = $prevShift->to;

            $shiftFromDateTime = \Carbon\Carbon::parse($prevDate . ' ' . $shiftFrom);
            $shiftToDateTime = \Carbon\Carbon::parse($prevDate . ' ' . $shiftTo);
            if ($shiftToDateTime->lt($shiftFromDateTime)) { // Overnight shift
                $shiftToDateTime->addDay();
            }

            // Window ends 7 hours after shift end
            $windowEndDateTime = $shiftToDateTime->copy()->addHours(7);
            // Window starts 2 hours before shift start to allow early check-ins
            $windowStartDateTime = $shiftFromDateTime->copy()->subHours(2);

            if ($dateTime->gte($windowStartDateTime) && $dateTime->lte($windowEndDateTime)) {
                return $prevDate;
            }
        }

        return $dateStr;
    }

    /**
     * Compare two times relative to the shift start time.
     * Times before the shift start are treated as next-day (add 24h) for correct chronological ordering.
     * @param string $a First time string (H:i:s)
     * @param string $b Second time string (H:i:s)
     * @param string $shiftStart Shift start reference time (H:i:s), defaults to '00:00:00'
     */
    private function _compareTimes($a, $b, $shiftStart = '00:00:00')
    {
        if (is_null($a) && is_null($b)) return 0;
        if (is_null($a)) return 1;
        if (is_null($b)) return -1;

        $refParts = explode(':', $shiftStart);
        $refSeconds = ((int)$refParts[0] * 3600) + ((int)($refParts[1] ?? 0) * 60) + ((int)($refParts[2] ?? 0));

        $toSeconds = function($t) use ($refSeconds) {
            $parts = explode(':', $t);
            $s = ((int)$parts[0] * 3600) + ((int)$parts[1] * 60) + ((int)($parts[2] ?? 0));
            
            // Set the start of the logical day 4 hours before the shift start to allow for early check-ins
            $logicalDayStart = $refSeconds - (4 * 3600);
            
            $shifted = ($s - $logicalDayStart) % 86400;
            if ($shifted < 0) {
                $shifted += 86400;
            }
            return $shifted;
        };

        return $toSeconds($a) - $toSeconds($b);
    }

    /**
     * إرجاع قائمة الموظفين المطلوب مزامنتهم إلى ماكينة البصمة عبر الوسيط
     * يُستخدم من الوسيط (zk_mediator.php) عبر: GET /api/zkt/users?device_ip=...&device_name=...
     *
     * يُرجع الموظفين الذين:
     * - لم يتم مزامنتهم بعد (zkt_synced = 0 أو null) في نطاق الجهاز
     * - أو تم طلب إعادة المزامنة (force=1)
     */
    public function getUsersForDevice(\Illuminate\Http\Request $request)
    {
        $deviceIp   = $request->input('device_ip');
        $deviceName = $request->input('device_name');
        $force      = $request->boolean('force', false);
        $deviceKey  = $deviceName ?: ($deviceIp ?: 'default');

        // جلب إعدادات الجهاز من قاعدة البيانات إن وجدت
        $device = null;
        if ($deviceIp) {
            $device = ipzkt::where('ip', $deviceIp)->orWhere('name', $deviceIp)->first();
        }
        if (!$device && $deviceName) {
            $device = ipzkt::where('name', $deviceName)->orWhere('ip', $deviceName)->first();
        }

        // تحديد مفاتيح الجهاز للمطابقة الموثوقة في zkt_device_sync
        $deviceKeys = array_unique(array_filter([
            $deviceKey,
            $deviceName,
            $deviceIp,
            $device ? $device->name : null,
            $device ? $device->ip : null,
            $device ? $device->sn : null,
        ]));

        // تحديد نطاق أرقام الموظفين للجهاز
        $query = employees::query()->whereNotNull('num');

        if ($device && $device->start > 0 && $device->end > 0) {
            $query->whereBetween('num', [$device->start, $device->end]);
        }

        if ($device) {
            $devIp   = $device->ip;
            $devSn   = $device->sn;
            $devName = $device->name;
            $query->where(function ($q) use ($devIp, $devSn, $devName) {
                $q->whereNull('ipz')
                  ->orWhere('ipz', '')
                  ->orWhere('ipz', 'LIKE', "%{$devIp}%");
                if ($devSn) {
                    $q->orWhere('ipz', 'LIKE', "%{$devSn}%");
                }
                if ($devName) {
                    $q->orWhere('ipz', 'LIKE', "%{$devName}%");
                }
            });
        }

        // إذا لم يُطلب Force، نرجع فقط الموظفين الذين لم يُزامنوا بعد لهذا الجهاز تحديداً
        if (!$force && !empty($deviceKeys)) {
            $alreadySynced = DB::table('zkt_device_sync')
                ->whereIn('device_key', $deviceKeys)
                ->pluck('emp_num')
                ->toArray();

            if (!empty($alreadySynced)) {
                $query->whereNotIn('num', $alreadySynced);
            }
        }

        $employeesList = $query->get(['id', 'num', 'name', 'password'])->map(function ($emp) {
            return [
                'uid'      => (int) $emp->num,   // UID = رقم الموظف (max 65535)
                'userid'   => (string) $emp->num, // User ID في الماكينة
                'name'     => mb_substr($emp->name ?? '', 0, 24), // max 24 حرف
                'password' => $emp->password ?? '',
                'role'     => 0, // 0 = User عادي, 14 = Admin
            ];
        });

        return response()->json([
            'status'    => 'success',
            'device'    => $device ? $device->name : $deviceKey,
            'count'     => $employeesList->count(),
            'employees' => $employeesList,
        ]);
    }

    /**
     * يستقبل تأكيد من الوسيط بأن الموظفين تمت مزامنتهم بنجاح إلى ماكينة الفرع
     * يُستخدم من الوسيط عبر: POST /api/zkt/users/synced
     */
    public function confirmUsersSynced(\Illuminate\Http\Request $request)
    {
        $syncedNums = $request->input('synced_nums', []);
        $deviceIp   = $request->input('device_ip');
        $deviceName = $request->input('device_name');
        $deviceKey  = $deviceName ?: ($deviceIp ?: 'default');

        if (empty($syncedNums) || !is_array($syncedNums)) {
            return response()->json(['status' => 'error', 'message' => 'No synced_nums provided'], 422);
        }

        $device = null;
        if ($deviceIp) {
            $device = ipzkt::where('ip', $deviceIp)->orWhere('name', $deviceIp)->first();
        }
        if (!$device && $deviceName) {
            $device = ipzkt::where('name', $deviceName)->orWhere('ip', $deviceName)->first();
        }

        $keysToSave = array_unique(array_filter([
            $deviceKey,
            $deviceName,
            $deviceIp,
            $device ? $device->name : null,
            $device ? $device->ip : null,
        ]));

        // تحديث جدول المزامنة لكل جهاز فرع على حدة
        foreach ($syncedNums as $num) {
            foreach ($keysToSave as $k) {
                DB::table('zkt_device_sync')->updateOrInsert(
                    ['device_key' => $k, 'emp_num' => (string)$num],
                    ['synced_at' => now(), 'updated_at' => now()]
                );
            }
        }

        // تحديث حالة zkt_synced العامة كعلامة إضافية
        employees::whereIn('num', $syncedNums)->update(['zkt_synced' => 1]);

        Log::info("ZK User Sync: Device [$deviceKey] synced " . count($syncedNums) . " employees. nums=" . implode(',', $syncedNums));

        return response()->json([
            'status'  => 'success',
            'message' => "Marked " . count($syncedNums) . " employee(s) as synced for device $deviceKey",
            'updated' => count($syncedNums),
        ]);
    }

    /**
     * جلب قائمة الموظفين المحذوفين الذين يحتاجون للحذف من ماكينة البصمة لهذا الفرع
     * يُستخدم من الوسيط عبر: GET /api/zkt/users/deleted?device_ip=...&device_name=...
     */
    public function getDeletedUsersForDevice(\Illuminate\Http\Request $request)
    {
        $deviceIp   = $request->input('device_ip');
        $deviceName = $request->input('device_name');
        $deviceKey  = $deviceName ?: ($deviceIp ?: 'default');

        $query = \App\Models\ZktDeletedUser::query();

        // تصفية الأجهزة: إما أمر عام لكل الأجهزة (null) أو مخصص لهذا الجهاز
        if ($deviceKey) {
            $query->where(function ($q) use ($deviceKey, $deviceName, $deviceIp) {
                $q->whereNull('device_key')
                  ->orWhere('device_key', '')
                  ->orWhere('device_key', $deviceKey);
                if ($deviceName) {
                    $q->orWhere('device_key', $deviceName);
                }
                if ($deviceIp) {
                    $q->orWhere('device_key', $deviceIp);
                }
            });

            // استبعاد الموظفين الذين تم حذفهم وتأكيدهم من هذا الجهاز مسبقاً
            $alreadyDeleted = DB::table('zkt_device_deletions')
                ->where('device_key', $deviceKey)
                ->pluck('emp_num')
                ->toArray();

            if (!empty($alreadyDeleted)) {
                $query->whereNotIn('num', $alreadyDeleted);
            }
        }

        $deletedList = $query->orderBy('id', 'asc')->get(['id', 'num', 'name'])->map(function ($item) {
            return [
                'id'   => $item->id,
                'uid'  => (int) $item->num,
                'pin'  => (string) $item->num,
                'name' => $item->name ?? '',
            ];
        });

        return response()->json([
            'status'        => 'success',
            'count'         => $deletedList->count(),
            'deleted_users' => $deletedList,
        ]);
    }

    /**
     * تأكيد حذف الموظفين من ماكينة البصمة لهذا الفرع عبر الوسيط
     * يُستخدم من الوسيط عبر: POST /api/zkt/users/deleted/confirm
     */
    public function confirmDeletedUsersSynced(\Illuminate\Http\Request $request)
    {
        $deletedPins = $request->input('deleted_pins', []);
        $deviceIp    = $request->input('device_ip');
        $deviceName  = $request->input('device_name');
        $deviceKey   = $deviceName ?: ($deviceIp ?: 'default');

        if (empty($deletedPins) || !is_array($deletedPins)) {
            return response()->json(['status' => 'error', 'message' => 'No deleted_pins provided'], 422);
        }

        // تسجيل إتمام الحذف لهذا الجهاز على حدة
        foreach ($deletedPins as $pin) {
            DB::table('zkt_device_deletions')->updateOrInsert(
                ['device_key' => $deviceKey, 'emp_num' => (string)$pin],
                ['deleted_at' => now(), 'updated_at' => now()]
            );
        }

        Log::info("ZK Deleted User Sync: Device [$deviceKey] confirmed deletion of " . count($deletedPins) . " users. pins=" . implode(',', $deletedPins));

        return response()->json([
            'status'  => 'success',
            'message' => "Marked " . count($deletedPins) . " user(s) as deleted on device $deviceKey",
            'updated' => count($deletedPins),
        ]);
    }

}
