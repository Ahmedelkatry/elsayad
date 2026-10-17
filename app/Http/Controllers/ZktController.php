<?php

namespace App\Http\Controllers;

use App\Http\Middleware\ahmed;
use App\Models\change_shift;
use App\Models\count;
use App\Models\counts_emps;
use App\Models\eid;
use App\Models\employees;
use App\Models\forget_finger;
use App\Models\head_sections;
use App\Models\loan;
use App\Models\nozkt;
use App\Models\please;
use App\Models\plzot;
use App\Models\section;
use App\Models\shift;
use App\Models\shift_week;
use App\Models\SupShift;
use App\Models\vacation;
use App\Models\zkt;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Support\Facades\DB;
use Rats\Zkteco\Lib\ZKTeco;

use function Laravel\Prompts\form;

class ZktController extends Controller implements HasMiddleware
{
    /**
     * Display a listing of the resource.
     */
    public function connect(Request $request)
    {
        // dd($request->ipz);
        $zk = new ZKTeco($request->ipz);
        $zk->connect();
        if ($zk) {
            $zk->testVoice();

            return back();
        } else {
        }
    }

    public function index(Request $request)
    {
        $zktss = \App\Models\ipzkt::all();
        return view('zktip.zkt', compact('zktss'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        // 1. التحقق من المدخلات (Validation)
        if (! app()->runningInConsole()) {
            $rules = [
                'searchs' => 'required',
                'searche' => 'required',
            ];
            if (! $request->has('local')) {
                // يجب اختيار إما IP أو SN
                if (! $request->has('ipz') && ! $request->has('snz')) {
                    $rules['device_selection'] = 'required';
                }
            }
            $request->validate($rules, [
                'device_selection.required' => 'يجب اختيار جهاز بصمة واحد على الأقل',
                'searchs.required' => 'يجب إدخال بداية التاريخ',
                'searche.required' => 'يجب إدخال نهاية التاريخ',
            ]);
        }

         plzot::where("status",0)->delete();
        count::truncate();
        // 2. التحقق من وجود بيانات موظفين
        $noData = DB::table('employees')->whereNull('free_d')->exists() &&
                  DB::table('employees')->whereNull('kabd')->exists();
        if ($noData) {
            if (! app()->runningInConsole()) {
                session()->flash('nodata');
            }

            return app()->runningInConsole() ? null : redirect()->back();
        }

        // 3. (تم إلغاء تصفير جدول الحسابات، سيتم التحديث بدلاً من الحذف لتجنب فقدان البيانات)

        // 4. سحب البيانات من أجهزة ZKTeco
        $attendanceRaw = [];

        // التحقق من نوع الأجهزة المدخلة
        $ips = [];
        $hasAdmsOnlyDevices = false; // أجهزة SN بدون IP (تعمل عبر ADMS فقط)

        if ($request->has('snz') && is_array($request->snz)) {
            $snList = $request->snz;
            $devicesFromSn = \App\Models\ipzkt::whereIn('sn', $snList)->get();
            foreach ($devicesFromSn as $dev) {
                if ($dev->ip) {
                    // جهاز SN لكن عنده IP → نتصل به مباشرة
                    $ips[] = $dev->ip;
                } else {
                    // جهاز SN بدون IP → يعمل عبر ADMS فقط (لا نحتاج اتصال مباشر)
                    $hasAdmsOnlyDevices = true;
                }
            }
        }

        if ($request->has('ipz') && is_array($request->ipz)) {
            $ips = array_merge($ips, $request->ipz);
        }

        $ips = array_unique(array_filter($ips));

        // إذا كانت كل الأجهزة ADMS فقط (بدون IP)، نستخدم البيانات المحلية المخزنة
        if ($hasAdmsOnlyDevices && empty($ips) && !$request->local && !$request->input('local')) {
            $request->merge(['local' => '1']);
            $request->query->set('local', '1');
            $request->request->set('local', '1');
        }

        if (!$request->local && !$request->input('local')) {
            if (empty($ips)) {
                if (! app()->runningInConsole()) {
                    session()->flash('error');
                }
                return app()->runningInConsole() ? null : redirect()->back();
            }

            $connectedAny = false;
            foreach ($ips as $ip) {
                // مزامنة أي موظفين معلقين تلقائياً إذا كان الجهاز متصلاً
                \App\Http\Controllers\IpzktController::syncPendingEmployeesToDevice($ip);

                $zk = new ZKTeco($ip);
                if ($zk->connect()) {
                    $zk->testVoice();
                    $att = $zk->getAttendance();
                    if ($att) {
                        $attendanceRaw = array_merge($attendanceRaw, $att);
                    }
                    $zk->disconnect();
                    $connectedAny = true;
                }
            }

            if (!$connectedAny) {
                if ($hasAdmsOnlyDevices) {
                    // لم نتمكن من الاتصال بأي جهاز، نستخدم البيانات المخزنة محلياً
                    $request->merge(['local' => '1']);
                    $request->query->set('local', '1');
                    $request->request->set('local', '1');
                } else {
                    if (! app()->runningInConsole()) {
                        session()->flash('error');
                    }
                    return app()->runningInConsole() ? null : redirect()->back();
                }
            }
        }

        // 5. تجهيز نطاق التواريخ
        $startDate = Carbon::createFromFormat('Y-m-d', $request->searchs);
        $endDate = Carbon::createFromFormat('Y-m-d', $request->searche);
        $dateRange = [];
        for ($date = clone $startDate; $date->lte($endDate); $date->addDay()) {
            $dateRange[] = $date->format('Y-m-d');
        }

        // 6. تنظيم بيانات الحضور المستلمة حسب الموظف والتاريخ
        $attendanceByEmployee = [];
        $extraDates = [];
        foreach ($attendanceRaw as $data) {
            $empId = $data['id'];
            $timestamp = Carbon::parse($data['timestamp']);

            $emp = employees::where('num', $empId)->first();
            $dateStr = $this->_resolvePunchDate($emp, $timestamp);
            $extraDates[] = $dateStr;
            $timeStr = Carbon::parse($data['timestamp'])->format('H:i:s');
            $type = $data['type']; // 0 دخول، 1 خروج، 3 استراحة دخول، 4 استراحة خروج

            if (! isset($attendanceByEmployee[$empId][$dateStr])) {
                $attendanceByEmployee[$empId][$dateStr] = [
                    'punches' => [],
                    'b_out' => null,
                    'b_in' => null,
                ];
            }

            $current = &$attendanceByEmployee[$empId][$dateStr];
            if ($type == 0 || $type == 1) {
                $current['punches'][] = $timeStr;
            } elseif ($type == 3) {
                $current['b_in'] = $timeStr;
            } elseif ($type == 4) {
                $current['b_out'] = $timeStr;
            }
        }

        // Expand dateRange to include extra dates that received shifted punches
        if (!empty($extraDates)) {
            $dateRange = array_unique(array_merge($dateRange, $extraDates));
            sort($dateRange);
        }

        // 7. تحديث جدول ZKTs (السجلات الأساسية) لكل الموظفين والتوارِيخ
        if (!$request->local && !$request->input('local')) {
            $allEmps = employees::all();

            // جلب السجلات الموجودة مسبقاً لتسريع العملية (تقليل الاستعلامات من آلاف إلى استعلام واحد)
            $existingZkts = Zkt::whereIn('date', $dateRange)->get()->groupBy(function ($q) {
                return $q->numid.'_'.$q->date;
            });

            DB::beginTransaction();
            try {
                foreach ($dateRange as $date) {
                    foreach ($allEmps as $emp) {
                        $empNum = $emp->num;
                        $key = $empNum.'_'.$date;

                        // البحث عن السجل في الذاكرة أولاً
                        $record = isset($existingZkts[$key]) ? $existingZkts[$key]->first() : null;

                        // تخطي السجل فقط إذا كان موجوداً ومكتملاً (يحتوي على دخول وخروج معاً)
                        if ($record && $record->in && $record->out) {
                            continue;
                        }

                        $data = $attendanceByEmployee[$empNum][$date] ?? null;

                        if (! $record) {
                            $record = new Zkt;
                            $record->numid = $empNum;
                            $record->name = $emp->name;
                            $record->date = $date;
                        }

                        // تحديد وقت بداية الشفت لترتيب البصمات بشكل صحيح
                        $empShiftForSort = $this->_getEmployeeShiftOnDate($emp, $date);
                        $shiftStartTime = $empShiftForSort ? $empShiftForSort->from : '00:00:00';

                        // Combine existing DB punches and new punches
                        $allPunches = [];
                        if ($record->in) $allPunches[] = $record->in;
                        if ($record->out) $allPunches[] = $record->out;

                        if ($data && !empty($data['punches'])) {
                            $allPunches = array_merge($allPunches, $data['punches']);
                        }

                        $allPunches = array_unique($allPunches);

                        if (count($allPunches) > 0) {
                            $shiftRef = $shiftStartTime;
                            usort($allPunches, function($a, $b) use ($shiftRef) {
                                return $this->_compareTimes($a, $b, $shiftRef);
                            });
                            $record->in = $allPunches[0];
                            if (count($allPunches) > 1) {
                                $record->out = end($allPunches);
                            } else {
                                $record->out = null;
                            }
                        }

                        if ($data) {
                            $record->break_out = $data['b_out'] ?? $record->break_out;
                            $record->break_in = $data['b_in'] ?? $record->break_in;
                        }

                        $status = 'غائب';
                        if ($record->in && $record->out) {
                            $status = 'حاضر';
                        }
                        $record->absent = $status;
                        $record->save();
                    }
                }
                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }

            // 8. الشفت المتداخل تتم معالجته تلقائياً بواسطة _resolvePunchDate أثناء جمع البصمات
        }

        // 9. التحميل المسبق للبيانات لعمليات الحساب (Performance Boost)
        $employeesWithRelations = employees::with(['shift', 'section'])->get();
        $vacations = vacation::all()->groupBy('employees_id');
        $permissions = please::all()->groupBy('employees_id');
        $forgetFingers = forget_finger::all()->groupBy('employees_id');
        $nozktData = nozkt::all()->groupBy('numid');
        $eids = eid::all()->pluck('date')->toArray();
        $supShifts = SupShift::all();
        $changeShifts = change_shift::all()->groupBy('employees_id');
        $headSections = head_sections::all()->groupBy('section_id');
        $shiftWeeks = shift_week::all()->groupBy('employees_id');

        // 10. الحلقة الرئيسية لحساب جدول counts
        $allZkts = zkt::whereIn('date', $dateRange)->get();
        $zktsGrouped = $allZkts->groupBy(function ($item) {
            return $item->numid.'_'.$item->date;
        });

        $processedZkts = [];
        foreach ($dateRange as $date) {
            foreach ($employeesWithRelations as $emp) {
                $key = $emp->num.'_'.$date;
                if (isset($zktsGrouped[$key])) {
                    $processedZkts[] = $zktsGrouped[$key]->first();
                } else {
                    $mockZkt = new zkt;
                    $mockZkt->numid = $emp->num;
                    $mockZkt->date = $date;
                    $mockZkt->in = null;
                    $mockZkt->out = null;
                    $mockZkt->absent = 'غائب';
                    $processedZkts[] = $mockZkt;
                }
            }
        }

        // جلب السجلات الحالية من جدول الحسابات لتحديثها بدلاً من إنشائها من جديد (لتحسين الأداء)
        $existingCounts = count::whereIn('date', $dateRange)->get()->groupBy(function ($item) {
            return $item->employees_id.'_'.$item->date;
        });

        foreach ($processedZkts as $zktRecord) {
            $empId = $zktRecord->numid;
            $currentDate = $zktRecord->date;
            $emp = $employeesWithRelations->where('num', $empId)->first();
            if (! $emp) {
                continue;
            }

            // أ. معالجة البيانات من nozkt و forget_finger
            $checkIn = $zktRecord->in;
            $checkOut = $zktRecord->out;

            // دمج بيانات الـ nozkt
            $nz = isset($nozktData[$empId]) ? $nozktData[$empId]->where('date', $currentDate)->first() : null;
            if ($nz) {
                if (! $checkIn && $nz->in) {
                    $checkIn = $nz->in;
                }
                if (! $checkOut && $nz->out) {
                    $checkOut = $nz->out;
                }
            }

            // دمج بيانات نسيان البصمة
            $ff = isset($forgetFingers[$emp->id]) ? $forgetFingers[$emp->id]->where('date', $currentDate)->first() : null;
            if ($ff) {
                if (! $checkIn) {
                    $checkIn = $ff->in;
                }
                if (! $checkOut) {
                    $checkOut = $ff->out;
                }
            }

            // ب. تحديد الشفت الفعلي لهذا اليوم (طوارئ، استثناءات، تبديل)
            $actualShiftId = $emp->shift_id;
            $actualShift = $emp->shift;

            // 1. شفت القسم
            $hs = isset($headSections[$emp->section_id]) ? $headSections[$emp->section_id]->where('date_start', '<=', $currentDate)->where('date_end', '>=', $currentDate)->first() : null;
            if ($hs) {
                $actualShiftId = $hs->shift_id;
                $actualShift = shift::find($actualShiftId);
            }

            // 2. تبديل شيفت فردي
            $cs = isset($changeShifts[$emp->id]) ? $changeShifts[$emp->id]->where('date_start', '<=', $currentDate)->where('date_end', '>=', $currentDate)->first() : null;
            if ($cs) {
                $actualShiftId = $cs->shift_id;
                $actualShift = shift::find($actualShiftId);
            }

            // 3. شيفت الأسبوع
            $sw = isset($shiftWeeks[$emp->id]) ? $shiftWeeks[$emp->id]->where('date', $currentDate)->first() : null;
            if ($sw) {
                $actualShiftId = $sw->shift_id;
                $actualShift = shift::find($actualShiftId);
            }

            // 4. شيفت استثنائي (رمضان مثلاً)
            $ss = $supShifts->where('shift_id', $actualShiftId)->where('start_date', '<=', $currentDate)->where('end_date', '>=', $currentDate)->first();
            if ($ss) {
                $actualShiftId = $ss->override_shift_id;
                $actualShift = shift::find($actualShiftId);
            }

            if (! $actualShift) {
                continue;
            }

            // ج. الحسابات المالية (راتب اليوم، قيمة الساعة)
            $monthStart = Carbon::parse($request->searchs)->startOfMonth();
            $monthEnd = Carbon::parse($request->searchs)->endOfMonth();
            $workingDaysCount = 30; // القيمة الافتراضية

            if ($emp->choosesys == 1) {
                // نظام الشهر الثابت (30 يوم)
                $workingDaysCount = 30;
            } elseif ($emp->choosesys == 2) {
                // نظام الشهر المتغير (أيام الشهر - العطلات)
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
                // نظام تحديد عدد أيام معين (مثلاً 26 يوم)
                $workingDaysCount = $emp->sysemp > 0 ? $emp->sysemp : 30;
            }

            $dayValue = $workingDaysCount > 0 ? ($emp->kabd / $workingDaysCount) : 0;
            $hourValue = $actualShift->workh > 0 ? ($dayValue / $actualShift->workh) : 0;

            // د. إنشاء أو تحديث سجل الحسابات (Count Record)
            $countKey = $emp->id.'_'.$currentDate;
            $count = isset($existingCounts[$countKey]) ? $existingCounts[$countKey]->first() : new count;
            $count->numid = $empId;
            $count->employees_id = $emp->id;
            $count->name = $emp->name;
            $count->date = $currentDate;
            $count->in = $checkIn;
            $count->out = $checkOut;
            $count->latitude_in = $zktRecord->latitude_in;
            $count->longitude_in = $zktRecord->longitude_in;
            $count->latitude_out = $zktRecord->latitude_out;
            $count->longitude_out = $zktRecord->longitude_out;
            $count->break_in = $zktRecord->break_in;
            $count->break_out = $zktRecord->break_out;
            $count->shift_id = $actualShiftId;
            $count->section_id = $emp->section_id;
            $count->in_duty = $actualShift->from;
            $count->out_duty = $actualShift->to;
            $count->kabdd = $emp->kabd;

            // هـ. حساب التأخير (Late) والإضافي (OT) بناءً على نوع الوردية (ثابتة أم ساعات)
            $timeIn = $checkIn ? Carbon::parse($currentDate.' '.$checkIn) : null;
            $timeOut = $checkOut ? Carbon::parse($currentDate.' '.$checkOut) : null;

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

            // حساب إجمالي ثواني العمل (Day Hours in seconds)
            if ($timeIn && $timeOut) {
                if ($timeOut->lt($timeIn)) {
                    $timeOut->addDay();
                }
                ($count->dayh = $timeIn->diffInSeconds($timeOut));
            } else {
                $count->dayh = 0;
            }

            $actualLateMinutes = 0;
            $actualEarlyMinutes = 0;

            if ($actualShift->shift_time === 0) {
                // وردية بالساعات (مثلاً مطلوب 8 ساعات عمل بغض النظر عن وقت الحضور)
                $requiredMinutes = $actualShift->workh * 60;
                $workedMinutes = $count->dayh / 60;
                $missingMinutes = $requiredMinutes - $workedMinutes;

                if ($missingMinutes > 0) {
                    $actualLateMinutes = $missingMinutes;
                    $count->early = Carbon::parse('00:00:00')->addMinutes($missingMinutes);

                    // الخصم بناءً على قواعد التأخير المطبقة على الدقائق الناقصة
                    if ($actualShift->late3 > 0 && $missingMinutes >= $actualShift->late3) {
                        $count->dis = round($actualShift->dism3 * ($hourValue / 60), 2);
                    } elseif ($actualShift->late2 > 0 && $missingMinutes >= $actualShift->late2) {
                        $count->dis = round($actualShift->dism2 * ($hourValue / 60), 2);
                    } elseif ($actualShift->late1 > 0 && $missingMinutes >= $actualShift->late1) {
                        $count->dis = round($actualShift->dism1 * ($hourValue / 60), 2);
                    } elseif ($actualShift->late > 0 && $missingMinutes >= $actualShift->late) {
                        $count->dis = round($actualShift->dism * ($hourValue / 60), 2);
                    } else {
                        $count->dis = 0;
                    }
                }

                if ($workedMinutes > $requiredMinutes) {
                    $count->othours = ($workedMinutes - $requiredMinutes) * 60;
                    $count->ot = $timeOut->format('H:i:s');
                }
            } else {
                // وردية ثابتة (وقت دخول ووقت خروج محدد)
                $baseDate = $currentDate;
                $shiftFrom = Carbon::parse($baseDate.' '.$actualShift->from);
                $shiftTo = Carbon::parse($baseDate.' '.$actualShift->to);
                $startOt = Carbon::parse($baseDate.' '.$actualShift->start_ot);

                // معالجة الانتقال لليوم التالي في مواعيد الوردية
                if ($shiftTo->lt($shiftFrom)) {
                    $shiftTo->addDay();
                }
                if ($startOt->lt($shiftFrom)) {
                    $startOt->addDay();
                }
                // dd($count->name);
                // 1. التأخير
                if ($timeIn && $timeIn->gt($shiftFrom->copy()->addMinutes($actualShift->late))) {
                    $lateMinutes = $shiftFrom->diffInMinutes($timeIn);
                    $actualLateMinutes = $lateMinutes;
                    $count->early = Carbon::parse('00:00:00')->addMinutes($lateMinutes);

                    if ($actualShift->late3 > 0 && $lateMinutes >= $actualShift->late3) {
                        $count->dis = round($actualShift->dism3 * ($hourValue / 60), 2);
                    } elseif ($actualShift->late2 > 0 && $lateMinutes >= $actualShift->late2) {
                        $count->dis = round($actualShift->dism2 * ($hourValue / 60), 2);
                    } elseif ($actualShift->late1 > 0 && $lateMinutes >= $actualShift->late1) {
                        $count->dis = round($actualShift->dism1 * ($hourValue / 60), 2);
                    } else {
                        $count->dis = round($actualShift->dism * ($hourValue / 60), 2);
                    }
                }

                // 2. الخروج المبكر
                if ($timeOut && $timeOut->lt($shiftTo->copy()->subMinutes($actualShift->bdry))) {
                    $earlyMinutes = $timeOut->diffInMinutes($shiftTo);
                    $actualEarlyMinutes = $earlyMinutes;
                    $count->disout = round($actualShift->disout * ($hourValue / 60) * $earlyMinutes, 2);
                }

                // 3. الإضافي (Overtime)
                if ($timeOut && $timeOut->gt($startOt)) {
                    $otSeconds = $startOt->diffInSeconds($timeOut);
                    $count->othours = $otSeconds;
                    $count->ot = $timeOut->format('H:i:s');
                }
            }

            // 4. معالجة طلبات الإضافي (Overtime Requests)
            if ($count->othours > 0) {
                // إنشاء أو تحديث طلب إضافي تلقائي بانتظار الاعتماد
                plzot::firstOrCreate(
                    [
                        'numid' => $empId,
                        'date'  => $currentDate,
                    ],
                    [
                        'name'      => $emp->name,
                        'in_duty'   => $actualShift->from,
                        'out_duty'  => $actualShift->to,
                        'in'        => $checkIn,
                        'out'       => $checkOut,
                        'othours'   => $count->othours,
                        'count_ot'  => round((($actualShift->ot * $count->othours) / 3600) * $hourValue, 2),
                        'status'    => 0, // حالة الانتظار
                    ]
                );
            }

            // 5. حساب الإضافي المعتمد فقط (Approved OT)
            $approvedOt = plzot::where('numid', $empId)->where('date', $currentDate)->where('status', 1)->first();
            if ($approvedOt) {
                $count->count_ot = round((($actualShift->ot * $count->othours) / 3600) * $hourValue, 2);
            }

            // و. معالجة الإجازات (Vacations)، الاستئذانات، والغياب
            $vac = isset($vacations[$emp->id]) ? $vacations[$emp->id]->where('date', $currentDate)->first() : null;

            if ($vac) {
                $vac->disvac > 0 ?
                    $count->absent = 1 : $count->absent = 2;

                // قيمة الخصم بناءً على قيمة disvac في جدول الإجازات (عادة 0)
                $count->absent_dis = round($dayValue * $vac->disvac, 2);
                $count->dis = 0; // تصفير خصم التأخير
                $count->disout = 0; // تصفير الخروج المبكر
            } elseif (! $checkIn && ! $checkOut) {
                $count->absent = 1; // غائب
                $count->absent_dis = round($dayValue * $actualShift->absent, 2);
            } else {
                $count->absent = 2; // حاضر
            }

            // الاستئذانات
            $plz = isset($permissions[$emp->id]) ? $permissions[$emp->id]->where('date', $currentDate)->first() : null;
            if ($plz) {
                // قيمة خصم الاستئذان (في حال تجاوز الموظف المدة المسموحة)

                $permissionPenalty = round(($plz->dis * ($hourValue / 60)) + $plz->dis_value, 2);

                if ($plz->in_out == 0) { // إذن دخول (تأخير)
                    if ($actualLateMinutes > $plz->minutes) {
                        // dd($actualLateMinutes,$count->date,$count->name );
                        //  dd($count->date);
                        // تجاوز مدة الإذن، نطبق خصم الاستئذان بدلاً من الخصم التلقائي
                        // $count->dis = $plz->dis * ($actualLateMinutes * ($hourValue / 60));
                        if ($plz->dis_value > 0) {
                            $count->dis = $plz->dis_value;
                        }
                        if ($plz->dis > 0) {
                            $count->dis = $plz->dis * ($actualLateMinutes * ($hourValue / 60));
                        }

                    } else {
                        // ضمن المدة المسموحة، يُعفى من خصم التأخير
                        $count->dis = 0;
                    }
                } else { // إذن خروج مبكر
                    if ($actualEarlyMinutes > $plz->minutes) {
                        // تجاوز مدة الإذن، نطبق خصم الاستئذان بدلاً من خصم الخروج المبكر
                        if ($plz->dis_value > 0) {
                            $count->disout = $plz->dis_value;
                        }
                        if ($plz->dis > 0) {
                            $count->disout = $plz->dis * ($actualEarlyMinutes * ($hourValue / 60));
                        }
                    } else {
                        // ضمن المدة المسموحة، يُعفى من خصم الخروج المبكر
                        $count->disout = 0;
                    }
                }

            }
            if (! $timeIn && !$timeOut || !$timeOut) {
                $count->dis = 0;
                $count->early = 0;
            }
            // الإجازات الرسمية والعطلات
            $isHoliday = in_array($currentDate, $eids);
            $dayName = Carbon::parse($currentDate)->locale('ar')->dayName;
            $isWeekend = is_array($emp->free_d) && in_array($dayName, $emp->free_d);

            if ($isHoliday || $isWeekend) {
                $count->absent = 0;
                $count->absent_dis = 0;

                if ($isHoliday) {
                    $count->eid = 1;
                }

                // إذا حضر الموظف في يوم الإجازة يتم احتساب مكافأة حضور (friday = 1)
                if ($timeIn) {
                    $count->friday = 1;
                } else {
                    $count->friday = null;
                }
            }

            // ز. الحفظ النهائي للسجل
            $count->save();

            // ح. تحديث جدول الموظف الفردي إذا لزم الأمر
            if ($request->emps == 1) {
                counts_emps::updateOrCreate(['numid' => $empId, 'date' => $currentDate], $count->toArray());
            }
        }

        // 11. معالجة السلف والقروض (Loans)
        $loans = loan::all();
        foreach ($loans as $loan) {
            $loanEmpId = $loan->employees_id;
            $start = Carbon::parse($loan->date_start)->startOfMonth();
            $end = Carbon::parse($loan->date_end);

            while ($start->lte($end)) {
                $dateString = $start->toDateString();
                count::where('date', $dateString)
                    ->where('employees_id', $loanEmpId)
                    ->update(['dis_loan' => $loan->mony_now]);
                $start->addMonth();
            }
        }

        return app()->runningInConsole() ? null : back();
    }

    public static function middleware()
    {
        return [
            ahmed::class,
        ];
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $exists_in = zkt::where('date', $request->date)->where('numid', $request->numid)->whereNotNull('in')->exists();

        if ($exists_in) {
            return response()->json([
                'success' => false,
                'message' => 'تم تسجيل دخول اليوم مسبقاً',
            ]);
        }

        try {
            zkt::updateOrCreate(
                ['numid' => $request->numid, 'date' => $request->date],
                [
                    'in' => $request->inn,
                    'name' => $request->name,
                    'absent' => 'حاضر',
                    'latitude_in' => $request->input('latitude'),
                    'longitude_in' => $request->input('longitude'),
                ]
            );

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function updateout(Request $request)
    {
        try {
            // نحاول التحديث في جدول zkts أولاً
            $updated = zkt::where('numid', $request->numid)
                ->where('date', $request->date)
                ->update([
                    'out' => $request->input('out'),
                    'latitude_out' => $request->input('latitude'),
                    'longitude_out' => $request->input('longitude'),
                ]);

            // لو ملقاش سجل في zkts، نجرب في nozkts (للتوافق مع الحركات القديمة اليوم)
            if ($updated === 0) {
                $nozkt = nozkt::where('numid', $request->numid)
                    ->where('date', $request->date)
                    ->first();

                if ($nozkt) {
                    $nozkt->update([
                        'out' => $request->input('out'),
                        'latitude_out' => $request->input('latitude'),
                        'longitude_out' => $request->input('longitude'),
                    ]);

                    // وننقل البيانات لـ zkts برضه عشان نرضي رغبة المستخدم في استخدام الجدول ده
                    zkt::updateOrCreate(
                        ['numid' => $request->numid, 'date' => $request->date],
                        [
                            'in' => $nozkt->in,
                            'out' => $request->input('out'),
                            'name' => $nozkt->name,
                            'absent' => 'حاضر',
                            'latitude_in' => $nozkt->latitude_in,
                            'longitude_in' => $nozkt->longitude_in,
                            'latitude_out' => $request->input('latitude'),
                            'longitude_out' => $request->input('longitude'),
                        ]
                    );
                    $updated = 1;
                }
            }

            if ($updated === 0) {
                return response()->json(['success' => false, 'message' => 'عذراً، لم يتم العثور على سجل دخول اليوم لإتمام عملية الخروج. يرجى التأكد من تسجيل الدخول أولاً.']);
            }

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function breakout(Request $request)
    {
        try {
            $updated = zkt::where('numid', $request->numid)
                ->where('date', $request->date)
                ->update([
                    'break_out' => $request->break_out,
                    'latitude_break_out' => $request->input('latitude'),
                    'longitude_break_out' => $request->input('longitude'),
                ]);

            if ($updated === 0) {
                // محاولة البحث في nozkts
                $nozkt = nozkt::where('numid', $request->numid)->where('date', $request->date)->first();
                if ($nozkt) {
                    $nozkt->update([
                        'break_out' => $request->break_out,
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
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function breakin(Request $request)
    {
        try {
            $updated = zkt::where('numid', $request->numid)
                ->where('date', $request->date)
                ->update([
                    'break_in' => $request->break_in,
                    'latitude_break_in' => $request->input('latitude'),
                    'longitude_break_in' => $request->input('longitude'),
                ]);

            if ($updated === 0) {
                // محاولة البحث في nozkts
                $nozkt = nozkt::where('numid', $request->numid)->where('date', $request->date)->first();
                if ($nozkt) {
                    $nozkt->update([
                        'break_in' => $request->break_in,
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
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(zkt $zkt)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(zkt $zkt)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function insert(Request $request)
    {
        $ips = is_array($request->ipz) ? $request->ipz : [];
        if (empty($ips)) {
            session()->flash('error');

            return redirect('/zkt');
        }

        if (shift::all()->count() == 0 || section::all()->count() == 0) {
            session()->flash('warning');

            return redirect('/zkt');
        }

        $success = false;

        foreach ($ips as $ip) {
            $zk = new ZKTeco($ip);
            if ($zk->connect()) {
                $zk->testVoice();
                $employees = $zk->getUser();

                if ($employees) {
                    foreach ($employees as $employee) {
                        ($fingerprints = $zk->getFingerprint($employee['uid']));
                        ($fingerprint_encoded = ! empty($fingerprints) ? base64_encode(serialize($fingerprints)) : null);

                        Employees::updateOrCreate(
                            [
                                'num' => $employee['userid'],
                            ],
                            [
                                'name' => $employee['name'],
                                'role_zkt' => 0,
                                'section_id' => 1,
                                'shift_id' => 1,
                                'fingerprint' => $fingerprint_encoded,
                                'role_zkt' => 0,
                            ]
                        );
                    }
                }
                $zk->disconnect();
                $success = true;
            }
        }

        if ($success) {
            session()->flash('Status');

            return redirect('/zkt');
        } else {
            session()->flash('error');

            return redirect('/zkt');
        }

        //    DB::table('zkts')->insert([
        // 'num' => $us["userid"],
        // 'name' => $us["name"],
        // 'in' => $checkIn,
        // 'out' => $checkOut,
        // 'absent' => $status,
        // 'created_at' => Carbon::now(),
        // 'updated_at' => Carbon::now(),
        // ]);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(zkt $zkt)
    {
        //
    }

    public function upload(Request $request)
    {
        set_time_limit(0); // إلغاء حد الوقت لأن رفع البصمات قد يستغرق وقتاً طويلاً

        $ips = is_array($request->ipz) ? $request->ipz : [];
        if (empty($ips)) {
            return back();
        }

        $emps = employees::get();

        foreach ($ips as $ip) {
            \Log::info("ZKTeco: Starting upload to $ip. Total employees to process: ".count($emps));
            $zk = new ZKTeco($ip);
            if ($zk->connect()) {
                $zk->testVoice();
                try {
                    // زيادة المهلة لـ 5 ثوانٍ لضمان استلام الرد من الأجهزة البطيئة
                    socket_set_option($zk->_zkclient, SOL_SOCKET, SO_RCVTIMEO, ['sec' => 5, 'usec' => 0]);
                    $zk->disableDevice();

                    $sendRaw = function (int $cmd, string $payload = '') use ($zk): int {
                        $session_id = $zk->_session_id;
                        $reply_id = 0;
                        if (strlen($zk->_data_recv) >= 8) {
                            $u = unpack('v', substr($zk->_data_recv, 6, 2));
                            $reply_id = $u[1];
                        }
                        $buf = \Rats\Zkteco\Lib\Helper\Util::createHeader($cmd, 0, $session_id, $reply_id, $payload);
                        socket_sendto($zk->_zkclient, $buf, strlen($buf), 0, $zk->_ip, $zk->_port);
                        $data = '';
                        @socket_recvfrom($zk->_zkclient, $data, 1024, 0, $zk->_ip, $zk->_port);
                        if (strlen($data) >= 8) {
                            $zk->_data_recv = $data;
                            $u2 = unpack('v', substr($data, 0, 2));

                            return $u2[1];
                        }

                        return -1;
                    };

                    foreach ($emps as $emp) {
                        $uid = (int) $emp->num;
                        if ($uid <= 0) {
                            continue;
                        }

                        $pass = ($emp->password === '' || $emp->password === null) ? null : $emp->password;
                        $role = (int) $emp->role_zkt;
                        if ($role !== 14) {
                            $role = 0;
                        } // حماية: المستخدم العادي 0 والمشرف 14

                        // 1. رفع البيانات الأساسية (الاسم والرقم)
                        $setRes = $zk->setUser($uid, $uid, $emp->name, $pass, $role);
                        usleep(20000); // 20ms delay

                        // 2. رفع البصمة إذا كانت موجودة في القاعدة
                        if (! empty($emp->fingerprint)) {
                            $raw = base64_decode($emp->fingerprint);
                            $tpls = ($raw !== false) ? @unserialize($raw) : null;

                            if (is_array($tpls) && ! empty($tpls)) {
                                $byte1 = chr($uid % 256);
                                $byte2 = chr($uid >> 8);

                                foreach ($tpls as $fid => $tpl) {
                                    if (! is_string($tpl) || strlen($tpl) <= 10) {
                                        continue;
                                    }

                                    // تعديل الهيدر ليناسب الـ UID الحالي
                                    $newTpl = $tpl;
                                    $newTpl[2] = $byte1;
                                    $newTpl[3] = $byte2;
                                    $newTpl[4] = chr((int) $fid);
                                    $size = strlen($newTpl);

                                    \Log::info("ZKTeco: Uploading finger $fid for user $uid (Size: $size bytes)");

                                    // أ. حذف البصمة القديمة لتجنب التعارض
                                    $sendRaw(\Rats\Zkteco\Lib\Helper\Util::CMD_DELETE_USER_TEMP, $byte1.$byte2.chr((int) $fid));
                                    usleep(10000);

                                    // ب. إخطار الجهاز ببدء نقل بيانات
                                    $res = $sendRaw(\Rats\Zkteco\Lib\Helper\Util::CMD_PREPARE_DATA, pack('V', $size));
                                    if ($res !== 2000) {
                                        \Log::error("ZKTeco: PREPARE_DATA failed (code $res) for user $uid finger $fid");

                                        continue;
                                    }
                                    usleep(10000);

                                    // ج. نقل البيانات على أجزاء (Chunks)
                                    $offset = 0;
                                    while ($offset < $size) {
                                        $chunk = substr($newTpl, $offset, 1016);
                                        $sendRaw(\Rats\Zkteco\Lib\Helper\Util::CMD_DATA, $chunk);
                                        $offset += 1016;
                                        usleep(5000);
                                    }

                                    // د. أمر الحفظ النهائي (Commit)
                                    $res = $sendRaw(\Rats\Zkteco\Lib\Helper\Util::CMD_USER_TEMP_WRQ, $byte1.$byte2.chr((int) $fid));
                                    if ($res === 2000) {
                                        \Log::info("ZKTeco: Finger $fid for user $uid SUCCESS");
                                    } else {
                                        \Log::error("ZKTeco: Finger $fid for user $uid FAIL (code $res)");
                                    }
                                    usleep(10000);
                                }
                            }
                        }
                    }

                    $sendRaw(7); // CMD_REFRESHDATA لضمان تحديث الجهاز
                    \Log::info("ZKTeco: Finished upload for IP $ip");

                } catch (\Exception $e) {
                    \Log::error('ZKTeco Exception: '.$e->getMessage());
                } finally {
                    $zk->enableDevice();
                    $zk->disconnect();
                }
                $success = true;
            } else {
                \Log::warning("ZKTeco: Could not connect to $ip");
            }
        }

        return back();
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
}
