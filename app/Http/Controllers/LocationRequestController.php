<?php

namespace App\Http\Controllers;

use App\Models\LocationRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LocationRequestController extends Controller
{
    // ================================================
    // Mobile App — تسجيل الدخول عبر الموبايل
    // ================================================
    public function mobileLogin(Request $request)
    {
        $request->validate([
            'phone' => 'required',
            'password' => 'required',
        ]);

        if (Auth::attempt($request->only('phone', 'password'))) {
            $user = Auth::user();

            // التحقق من صلاحية الموظف
            if ($user->Status !== 'مفعل') {
                Auth::logout();

                return response()->json([
                    'success' => false,
                    'message' => 'الحساب غير نشط أو معطل.',
                ], 403);
            }

            // التحقق من الـ User-Agent للموبايل أو قيود الأجهزة
            $deviceId = $request->header('Device-Id');

            if ($deviceId) {
                $mobileAgents = $user->user_agent_mobile;
                if (is_string($mobileAgents)) {
                    $decoded = json_decode($mobileAgents, true);
                    $mobileAgents = is_array($decoded) ? $decoded : [$mobileAgents];
                }
                $mobileAgents = is_array($mobileAgents) ? $mobileAgents : [];

                // إذا لم يكن لديه جهاز مسجل، قم بتسجيله
                if (empty($mobileAgents)) {
                    $user->user_agent_mobile = [$deviceId];
                    $user->save();
                } elseif (! in_array($deviceId, $mobileAgents)) {
                    Auth::logout();

                    return response()->json([
                        'success' => false,
                        'message' => 'لا يمكنك تسجيل الدخول من جهاز آخر.',
                    ], 403);
                }
            }

            // توليد جلسة جديدة وحفظها
            $request->session()->put('mobile_user_id', $user->id);
            $request->session()->regenerate();

            return response()->json([
                'success' => true,
                'token' => session()->getId(),
                'user_num' => $user->num,
                'user_name' => $user->name,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'رقم الهاتف أو كلمة المرور غير صحيحة.',
        ], 401);
    }

    // أرسل المدير طلباً للموظف
    public function sendRequest(Request $request)
    {
        $request->validate([
            'numid' => 'required',
        ]);

        if ($request->numid === 'all') {
            $employees = \App\Models\employees::all();
            foreach ($employees as $emp) {
                LocationRequest::create([
                    'numid' => $emp->num,
                    'admin_id' => Auth::id(),
                    'status' => 0, // Pending
                ]);
            }
        } else {
            LocationRequest::create([
                'numid' => $request->numid,
                'admin_id' => Auth::id(),
                'status' => 0, // Pending
            ]);
        }

        return response()->json(['message' => 'تم إرسال طلب الموقع بنجاح']);
    }

    // الموظف يتفقد وجود طلبات معلقة
    public function checkRequest()
    {
        // البحث عن آخر طلب معلق لهذا الموظف (باستخدام رقم البصمة)
        $employeeNum = Auth::user()->num; // نفترض أن رقم البصمة مخزن في num

        $pendingRequest = LocationRequest::where('numid', $employeeNum)
            ->where('status', 0)
            ->first();

        if ($pendingRequest) {
            return response()->json([
                'has_request' => true,
                'request_id' => $pendingRequest->id,
            ]);
        }

        return response()->json(['has_request' => false]);
    }

    // الموظف يرد على الطلب
    public function respondRequest(Request $request)
    {
        $request->validate([
            'request_id' => 'required',
            'lat' => 'required',
            'lon' => 'required',
        ]);

        if ($request->request_id === 'silent') {
            $numid = $request->numid ?? (Auth::check() ? Auth::user()->num : null);
            if ($numid) {
                // نسجل له طلب وهمي في حال أردنا تتبعه كآخر رد
                LocationRequest::updateOrCreate(
                    [
                        'numid' => $numid,
                        'admin_id' => 1, // معرف المدير الافتراضي
                        'status' => 1,
                    ],
                    [
                        'lat' => $request->lat,
                        'lon' => $request->lon,
                        'response_time' => Carbon::now(),
                    ]
                );
            }
        } else {
            $locRequest = LocationRequest::findOrFail($request->request_id);
            $locRequest->update([
                'lat' => $request->lat,
                'lon' => $request->lon,
                'status' => 1, // Responded
                'response_time' => Carbon::now(),
            ]);
        }

        return response()->json(['message' => 'تم إرسال موقعك بنجاح']);
    }

    // عرض الردود للمدير
    public function getLatestResponses()
    {
        $responses = LocationRequest::where('status', 1)
            ->join('employees', 'location_requests.numid', '=', 'employees.num')
            ->select('location_requests.*', 'employees.name as employee_name')
            ->orderBy('response_time', 'desc')
            ->take(5)
            ->get()
            ->map(function ($request) {
                return [
                    'id' => $request->id,
                    'employee_name' => $request->employee_name,
                    'lat' => $request->lat,
                    'lon' => $request->lon,
                    'time' => Carbon::parse($request->response_time)->format('H:i:s Y-m-d'),
                ];
            });

        return response()->json($responses);
    }

    // جلب المواقع المباشرة (بصمات اليوم + التتبع الصامت)
    public function getLiveLocations()
    {
        $today = Carbon::today()->toDateString();

        // 1. بصمات الحضور من جدول counts
        $countRecords = \App\Models\count::where('date', $today)
            ->where(function ($q) {
                $q->whereNotNull('latitude_in')->whereNotNull('longitude_in')
                    ->orWhereNotNull('latitude_out')->whereNotNull('longitude_out');
            })
            ->get()
            ->keyBy('num'); // مفهرسة برقم الموظف

        // 2. آخر تتبع صامت من جدول location_requests
        $silentRecords = LocationRequest::where('status', 1)
            ->whereDate('response_time', $today)
            ->join('employees', 'location_requests.numid', '=', 'employees.num')
            ->select('location_requests.*', 'employees.name as employee_name', 'employees.num as emp_num')
            ->orderBy('response_time', 'desc')
            ->get()
            ->unique('emp_num'); // آخر سجل لكل موظف

        // جلب بصمات اليوم للتحقق من حالة الدخول والخروج
        $zktRecords = \App\Models\zkt::where('date', $today)->get()->keyBy('numid');
        $nozktRecords = \App\Models\nozkt::where('date', $today)->get()->keyBy('numid');

        // 3. دمج البيانات
        $result = [];

        foreach ($countRecords as $num => $record) {
            $result[$num] = [
                'num' => $num,
                'employee_id' => $record->employees_id,
                'name' => $record->name,
                'lat_in' => $record->latitude_in,
                'lon_in' => $record->longitude_in,
                'lat_out' => $record->latitude_out,
                'lon_out' => $record->longitude_out,
                'in_time' => $record->in,
                'out_time' => $record->out,
                'live_lat' => null,
                'live_lon' => null,
                'live_time' => null,
                'live_response_time' => null,
                'is_online' => false,
            ];
        }

        foreach ($silentRecords as $silent) {
            $num = $silent->emp_num;

            // التحقق من حالة الحضور (يجب أن يكون قد سجل دخول ولم يسجل خروج)
            $hasIn = (isset($zktRecords[$num]) && ! empty($zktRecords[$num]->in)) || (isset($nozktRecords[$num]) && ! empty($nozktRecords[$num]->in));
            $hasOut = (isset($zktRecords[$num]) && ! empty($zktRecords[$num]->out)) || (isset($nozktRecords[$num]) && ! empty($nozktRecords[$num]->out));

            if (! $hasIn || $hasOut) {
                continue;
            }

            if (! isset($result[$num])) {
                $emp = \App\Models\employees::where('num', $num)->first();
                $result[$num] = [
                    'num' => $num,
                    'employee_id' => $emp ? $emp->id : null,
                    'name' => $silent->employee_name,
                    'lat_in' => null, 'lon_in' => null,
                    'lat_out' => null, 'lon_out' => null,
                    'in_time' => null, 'out_time' => null,
                    'live_lat' => null, 'live_lon' => null,
                    'live_time' => null, 'live_response_time' => null,
                    'is_online' => false,
                ];
            }
            $result[$num]['live_lat'] = $silent->lat;
            $result[$num]['live_lon'] = $silent->lon;
            $result[$num]['live_time'] = Carbon::parse($silent->response_time)->format('H:i:s');
            $result[$num]['live_response_time'] = Carbon::parse($silent->response_time)->toIso8601String();
            $result[$num]['is_online'] = Carbon::parse($silent->response_time)->diffInSeconds(Carbon::now()) <= 120;
        }

        return response()->json(array_values($result));
    }

    // ================================================
    // Mobile App — إرسال الموقع من التطبيق
    // ================================================
    public function mobileLocation(Request $request)
    {
        $request->validate([
            'numid' => 'required',
            'lat' => 'required|numeric',
            'lon' => 'required|numeric',
            'recorded_at' => 'nullable|string',
        ]);

        $numid = $request->numid;
        $lat = $request->lat;
        $lon = $request->lon;
        $recordedAt = $request->recorded_at ? \Carbon\Carbon::parse($request->recorded_at) : \Carbon\Carbon::now();

        // 1. تحديث الطلب الحالي (للمتابعة اللحظية)
        \App\Models\LocationRequest::updateOrCreate(
            [
                'numid' => $numid,
                'admin_id' => 1,
                'status' => 1,
            ],
            [
                'lat' => $lat,
                'lon' => $lon,
                'response_time' => $recordedAt,
            ]
        );

        // 2. تسجيل في سجل التتبع (History)
        $emp = \App\Models\employees::where('num', $numid)->first();
        if ($emp) {
            \App\Models\EmployeeLocation::create([
                'employees_id' => $emp->id,
                'latitude' => $lat,
                'longitude' => $lon,
                'recorded_at' => $recordedAt,
            ]);
        }

        return response()->json(['success' => true, 'message' => 'تم تحديث الموقع والسجل']);
    }

    // حذف سجل تحديد الموقع
    public function destroy($id)
    {
        $request = LocationRequest::findOrFail($id);
        $request->delete();

        return response()->json(['message' => 'تم حذف السجل بنجاح']);
    }

    // ================================================
    // Mobile App — إبلاغ عن اكتشاف Fake GPS
    // ================================================
    public function mockAlert(Request $request)
    {
        $request->validate([
            'numid' => 'required',
            'detected_at' => 'nullable|string',
        ]);

        $numid = $request->numid;
        $detectedAt = $request->detected_at ?? now()->toIso8601String();
        $emp = \App\Models\employees::where('num', $numid)->first();
        $empName = $emp ? $emp->name : "موظف ($numid)";

        // سجل في الـ log
        \Illuminate\Support\Facades\Log::warning("🚫 اكتشاف Fake GPS: الموظف [{$empName}] رقم [{$numid}] في {$detectedAt}");

        // أرسل إشعاراً لكل المستخدمين الذين لديهم صلاحية الإدارة
        $admins = \App\Models\User::whereJsonContains('roles_name', 'admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(new \App\Notifications\FakeGpsDetected($numid, $empName, $detectedAt));
        }

        return response()->json([
            'success' => true,
            'message' => 'تم استلام إبلاغ Fake GPS',
        ]);
    }

    private function getMobileUser()
    {
        if (Auth::check()) {
            return Auth::user();
        }

        // إذا فشل Auth::check (مثلاً بسبب مشاكل الكوكيز عبر ngrok)، نحاول البحث عن المستخدم في الجلسة
        $userId = session('mobile_user_id');
        if ($userId) {
            $user = \App\Models\User::find($userId);
            if ($user) {
                Auth::login($user);

                return $user;
            }
        }

        return null;
    }

    // ================================================
    // Mobile App — التحقق من أمان الجهاز (GPS/Root/Wi-Fi)
    // ================================================
    private function validateDeviceSecurity(Request $request)
    {
        $isMock = filter_var($request->input('is_mock_location', false), FILTER_VALIDATE_BOOLEAN);
        $isRooted = filter_var($request->input('is_rooted', false), FILTER_VALIDATE_BOOLEAN);
        $wifiMac = $request->input('wifi_mac'); // للتحقق مستقبلاً من راوتر الشركة

        if ($isMock) {
            return 'عذراً، لا يمكن تسجيل الحضور باستخدام موقع وهمي (Fake GPS).';
        }

        if ($isRooted) {
            return 'عذراً، لا يمكن استخدام التطبيق من جهاز غير محمي (Rooted/Jailbroken).';
        }

        return null;
    }

    // ================================================
    // Mobile App — جلب حالة حضور الموظف لليوم
    // ================================================
    public function attendanceStatus()
    {
        $user = $this->getMobileUser();
        if (! $user) {
            return response()->json(['success' => false, 'message' => 'غير مصرح بالدخول (الرجاء إعادة تسجيل الدخول)'], 401);
        }

        $today = date('Y-m-d');
        $yesterday = date('Y-m-d', strtotime('-1 day'));
        $user_num = $user->num;

        $emp = \App\Models\employees::where('num', $user_num)->first();

        // 1. التحقق من وجود بصمة دخول غير مغلقة في اليوم السابق أولاً
        $zktPrev = \App\Models\zkt::where('numid', $user_num)->where('date', $yesterday)->first();
        $nozktPrev = \App\Models\nozkt::where('numid', $user_num)->where('date', $yesterday)->first();
        $prevIn = ($zktPrev && $zktPrev->in) ? $zktPrev->in : (($nozktPrev && $nozktPrev->in) ? $nozktPrev->in : null);
        $prevOut = ($zktPrev && $zktPrev->out) ? $zktPrev->out : (($nozktPrev && $nozktPrev->out) ? $nozktPrev->out : null);

        $useYesterday = false;
        if ($prevIn && !$prevOut) {
            // التحقق من مرور أقل من 7 ساعات على نهاية الشفت السابق
            $prevShift = $emp ? $this->_getEmployeeShiftOnDate($emp, $yesterday) : null;
            if ($prevShift && !empty($prevShift->to)) {
                $shiftFrom = $prevShift->from;
                $shiftTo = $prevShift->to;

                $shiftToDateTime = \Carbon\Carbon::parse($yesterday . ' ' . $shiftTo);
                if ($shiftTo < $shiftFrom) { // overnight shift
                    $shiftToDateTime->addDay();
                }

                $windowEndDateTime = $shiftToDateTime->copy()->addHours(7);
                if (\Carbon\Carbon::now()->lte($windowEndDateTime)) {
                    $useYesterday = true;
                }
            }
        }

        if ($useYesterday) {
            $zkt = $zktPrev;
            $nozkt = $nozktPrev;
            $recordDate = $yesterday;
        } else {
            $zkt = \App\Models\zkt::where('numid', $user_num)->where('date', $today)->first();
            $nozkt = \App\Models\nozkt::where('numid', $user_num)->where('date', $today)->first();
            $recordDate = $today;
        }

        $dbIn = ($zkt && $zkt->in) ? $zkt->in : (($nozkt && $nozkt->in) ? $nozkt->in : null);

        $has_in = false;
        $has_out = false;
        $has_break_out = false;
        $has_break_in = false;

        if ($dbIn) {
            // التحقق من انتهاء الصلاحية (مرور 7 ساعات بعد وقت انتهاء الشيفت)
            $activeShift = $useYesterday ? $prevShift : ($emp ? $this->_getEmployeeShiftOnDate($emp, $recordDate) : null);
            if ($activeShift && !empty($activeShift->to)) {
                $shiftFrom = $activeShift->from;
                $shiftTo = $activeShift->to;

                $shiftToDateTime = \Carbon\Carbon::parse($recordDate . ' ' . $shiftTo);
                if ($shiftTo < $shiftFrom) {
                    $shiftToDateTime->addDay();
                }

                if (\Carbon\Carbon::now()->gt($shiftToDateTime->copy()->addHours(7))) {
                    // انتهت صلاحية البصمة — يُمكّن تسجيل دخول جديد
                    $has_in = false;
                } else {
                    $has_in = true;
                    $has_out = ($zkt && $zkt->out) || ($nozkt && $nozkt->out);
                    $has_break_out = ($zkt && $zkt->break_out) || ($nozkt && $nozkt->break_out);
                    $has_break_in = ($zkt && $zkt->break_in) || ($nozkt && $nozkt->break_in);
                }
            } else {
                $has_in = true;
                $has_out = ($zkt && $zkt->out) || ($nozkt && $nozkt->out);
                $has_break_out = ($zkt && $zkt->break_out) || ($nozkt && $nozkt->break_out);
                $has_break_in = ($zkt && $zkt->break_in) || ($nozkt && $nozkt->break_in);
            }
        }

        return response()->json([
            'success' => true,
            'has_in' => $has_in,
            'has_out' => $has_out,
            'has_break_out' => $has_break_out,
            'has_break_in' => $has_break_in,
        ]);
    }

    // ================================================
    // Mobile App — تسجيل الدخول (الحضور)
    // ================================================
    public function mobileCheckIn(Request $request)
    {
        $user = $this->getMobileUser();
        if (! $user) {
            return response()->json(['success' => false, 'message' => 'غير مصرح بالدخول'], 401);
        }

        $securityError = $this->validateDeviceSecurity($request);
        if ($securityError) {
            return response()->json(['success' => false, 'message' => $securityError], 403);
        }

        $date = date('Y-m-d');

        // التحقق من الحضور الحالي (تجنب تكرار الدخول)
        $statusRes = $this->attendanceStatus()->getData();
        if (isset($statusRes->has_in) && $statusRes->has_in) {
            return response()->json([
                'success' => false,
                'message' => 'تم تسجيل دخول اليوم مسبقاً',
            ]);
        }

        $latitude = $request->input('latitude');
        $longitude = $request->input('longitude');

        // ── التحقق من الموقع (مواقع الدخول) ───────────────────────────────────
        if ($latitude !== null && $longitude !== null) {
            $emp = \App\Models\employees::where('num', $user->num)->first();
            if ($emp) {
                // أولاً: مواقع الشركة المرتبطة بالموظف مع فلتر النوع (in أو both)
                $companyLocations = $emp->companyLocations()
                    ->wherePivotIn('type', ['in', 'both'])
                    ->get();
                if ($companyLocations->isNotEmpty()) {
                    $locationAllowed = false;
                    foreach ($companyLocations as $loc) {
                        $dist = \App\Models\EmployeeAllowedLocation::haversineDistance(
                            (float) $loc->latitude, (float) $loc->longitude,
                            (float) $latitude, (float) $longitude
                        );
                        if ($dist <= $loc->radius) {
                            $locationAllowed = true;
                            break;
                        }
                    }
                    if (! $locationAllowed) {
                        return response()->json([
                            'success' => false,
                            'message' => 'موقعك خارج نطاق مواقع الدخول المسموحة. تأكد أنك في أحد الفروع المعتمدة.',
                        ], 403);
                    }
                } else {
                    // ثانياً: المواقع الخاصة بالموظف (قديمة)
                    $allowedLocations = \App\Models\EmployeeAllowedLocation::where('employee_id', $emp->id)->get();
                    if ($allowedLocations->isNotEmpty()) {
                        $locationAllowed = false;
                        foreach ($allowedLocations as $loc) {
                            if ($loc->isWithinRadius((float) $latitude, (float) $longitude)) {
                                $locationAllowed = true;
                                break;
                            }
                        }
                        if (! $locationAllowed) {
                            return response()->json([
                                'success' => false,
                                'message' => 'موقعك غير مسموح به. تأكد أنك في أحد المواقع المعتمدة.',
                            ], 403);
                        }
                    }
                }
            }
        }
        // ──────────────────────────────────────────────────────────────────────

        try {
            \App\Models\zkt::updateOrCreate(
                ['numid' => $user->num, 'date' => $date],
                [
                    'in' => date('H:i:s'),
                    'name' => $user->name,
                    'absent' => 'حاضر',
                    'latitude_in' => $latitude,
                    'longitude_in' => $longitude,
                ]
            );

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // ================================================
    // Mobile App — تسجيل الخروج (الإنصراف)
    // ================================================
    public function mobileCheckOut(Request $request)
    {
        $user = $this->getMobileUser();
        if (! $user) {
            return response()->json(['success' => false, 'message' => 'غير مصرح بالدخول'], 401);
        }

        $securityError = $this->validateDeviceSecurity($request);
        if ($securityError) {
            return response()->json(['success' => false, 'message' => $securityError], 403);
        }

        $today = date('Y-m-d');
        $yesterday = date('Y-m-d', strtotime('-1 day'));
        $latitude = $request->input('latitude');
        $longitude = $request->input('longitude');

        // تحديد الموظف والشيفت
        $emp = \App\Models\employees::where('num', $user->num)->first();

        // 1. التحقق من وجود بصمة دخول غير مغلقة في اليوم السابق أولاً
        $zktPrev = \App\Models\zkt::where('numid', $user->num)->where('date', $yesterday)->first();
        $prevIn = ($zktPrev && $zktPrev->in) ? $zktPrev->in : null;
        $prevOut = ($zktPrev && $zktPrev->out) ? $zktPrev->out : null;

        $useYesterday = false;
        if ($prevIn && !$prevOut) {
            // التحقق من مرور أقل من 7 ساعات على نهاية الشفت السابق
            $prevShift = $emp ? $this->_getEmployeeShiftOnDate($emp, $yesterday) : null;
            if ($prevShift && !empty($prevShift->to)) {
                $shiftFrom = $prevShift->from;
                $shiftTo = $prevShift->to;

                $shiftToDateTime = \Carbon\Carbon::parse($yesterday . ' ' . $shiftTo);
                if ($shiftTo < $shiftFrom) { // overnight shift
                    $shiftToDateTime->addDay();
                }

                $windowEndDateTime = $shiftToDateTime->copy()->addHours(7);
                if (\Carbon\Carbon::now()->lte($windowEndDateTime)) {
                    $useYesterday = true;
                }
            }
        }

        $targetZkt = null;
        $targetDate = $today;

        if ($useYesterday) {
            $targetZkt = $zktPrev;
            $targetDate = $yesterday;
        } else {
            // شيفت عادي: نبحث في اليوم الحالي فقط
            $targetZkt = \App\Models\zkt::where('numid', $user->num)
                ->where('date', $today)
                ->whereNotNull('in')
                ->whereNull('out')
                ->first();
            $targetDate = $today;
        }

        // ── التحقق من الموقع (مواقع الخروج) ───────────────────────────────────
        if ($latitude !== null && $longitude !== null) {
            if ($emp) {
                $companyLocations = $emp->companyLocations()
                    ->wherePivotIn('type', ['out', 'both'])
                    ->get();
                if ($companyLocations->isNotEmpty()) {
                    $locationAllowed = false;
                    foreach ($companyLocations as $loc) {
                        $dist = \App\Models\EmployeeAllowedLocation::haversineDistance(
                            (float) $loc->latitude, (float) $loc->longitude,
                            (float) $latitude, (float) $longitude
                        );
                        if ($dist <= $loc->radius) {
                            $locationAllowed = true;
                            break;
                        }
                    }
                    if (! $locationAllowed) {
                        return response()->json([
                            'success' => false,
                            'message' => 'موقعك خارج نطاق مواقع الخروج المسموحة. تأكد أنك في أحد الفروع المعتمدة.',
                        ], 403);
                    }
                } else {
                    $allowedLocations = \App\Models\EmployeeAllowedLocation::where('employee_id', $emp->id)->get();
                    if ($allowedLocations->isNotEmpty()) {
                        $locationAllowed = false;
                        foreach ($allowedLocations as $loc) {
                            if ($loc->isWithinRadius((float) $latitude, (float) $longitude)) {
                                $locationAllowed = true;
                                break;
                            }
                        }
                        if (! $locationAllowed) {
                            return response()->json([
                                'success' => false,
                                'message' => 'موقعك غير مسموح به. تأكد أنك في أحد المواقع المعتمدة.',
                            ], 403);
                        }
                    }
                }
            }
        }
        // ──────────────────────────────────────────────────────────────────────

        try {
            if ($targetZkt) {
                // تحديث السجل المفتوح مباشرة بوقت الخروج الفعلي
                $targetZkt->out = date('H:i:s');
                $targetZkt->latitude_out = $latitude;
                $targetZkt->longitude_out = $longitude;
                $targetZkt->absent = 'حاضر';
                $targetZkt->save();

                return response()->json(['success' => true]);
            }

            // إذا لم يوجد في zkts، نبحث في nozkt
            $nozkt = \App\Models\nozkt::where('numid', $user->num)
                ->where('date', $targetDate)
                ->whereNotNull('in')
                ->whereNull('out')
                ->first();

            if ($nozkt) {
                $nozkt->update([
                    'out' => date('H:i:s'),
                    'latitude_out' => $latitude,
                    'longitude_out' => $longitude,
                ]);

                \App\Models\zkt::updateOrCreate(
                    ['numid' => $user->num, 'date' => $targetDate],
                    [
                        'in' => $nozkt->in,
                        'out' => date('H:i:s'),
                        'name' => $nozkt->name,
                        'absent' => 'حاضر',
                        'latitude_in' => $nozkt->latitude_in,
                        'longitude_in' => $nozkt->longitude_in,
                        'latitude_out' => $latitude,
                        'longitude_out' => $longitude,
                    ]
                );

                return response()->json(['success' => true]);
            }

            return response()->json(['success' => false, 'message' => 'عذراً، لم يتم العثور على سجل دخول لإتمام عملية الخروج. يرجى التأكد من تسجيل الدخول أولاً.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // ================================================
    // Mobile App — خروج استراحة
    // ================================================
    public function mobileBreakOut(Request $request)
    {
        $user = $this->getMobileUser();
        if (! $user) {
            return response()->json(['success' => false, 'message' => 'غير مصرح بالدخول'], 401);
        }

        $securityError = $this->validateDeviceSecurity($request);
        if ($securityError) {
            return response()->json(['success' => false, 'message' => $securityError], 403);
        }

        $date = date('Y-m-d');

        try {
            $updated = \App\Models\zkt::where('numid', $user->num)
                ->where('date', $date)
                ->update([
                    'break_out' => date('H:i:s'),
                    'latitude_break_out' => $request->input('latitude'),
                    'longitude_break_out' => $request->input('longitude'),
                ]);

            if ($updated === 0) {
                $nozkt = \App\Models\nozkt::where('numid', $user->num)->where('date', $date)->first();
                if ($nozkt) {
                    $nozkt->update([
                        'break_out' => date('H:i:s'),
                        'latitude_break_out' => $request->input('latitude'),
                        'longitude_break_out' => $request->input('longitude'),
                    ]);
                    $updated = 1;
                }
            }

            if ($updated === 0) {
                return response()->json(['success' => false, 'message' => 'عذراً، يجب تسجيل الدخول أولاً قبل تسجيل الاستراحة']);
            }

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // ================================================
    // Mobile App — دخول استراحة
    // ================================================
    public function mobileBreakIn(Request $request)
    {
        $user = $this->getMobileUser();
        if (! $user) {
            return response()->json(['success' => false, 'message' => 'غير مصرح بالدخول'], 401);
        }

        $securityError = $this->validateDeviceSecurity($request);
        if ($securityError) {
            return response()->json(['success' => false, 'message' => $securityError], 403);
        }

        $date = date('Y-m-d');

        try {
            $updated = \App\Models\zkt::where('numid', $user->num)
                ->where('date', $date)
                ->update([
                    'break_in' => date('H:i:s'),
                    'latitude_break_in' => $request->input('latitude'),
                    'longitude_break_in' => $request->input('longitude'),
                ]);

            if ($updated === 0) {
                $nozkt = \App\Models\nozkt::where('numid', $user->num)->where('date', $date)->first();
                if ($nozkt) {
                    $nozkt->update([
                        'break_in' => date('H:i:s'),
                        'latitude_break_in' => $request->input('latitude'),
                        'longitude_break_in' => $request->input('longitude'),
                    ]);
                    $updated = 1;
                }
            }

            if ($updated === 0) {
                return response()->json(['success' => false, 'message' => 'عذراً، يجب تسجيل خروج الاستراحة أولاً']);
            }

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
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
}
