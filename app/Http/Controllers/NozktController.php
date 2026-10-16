<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\eid;
use App\Models\zkt;
use App\Models\loan;
use App\Models\count;
use App\Models\nozkt;
use App\Models\plzot;
use App\Models\shift;
use App\Models\please;
use App\Models\section;
use App\Models\vacation;
use App\Models\employees;
use App\Models\counts_emps;
use App\Models\change_shift;
use Illuminate\Http\Request;
use App\Models\forget_finger;
use App\Models\head_sections;
use App\Models\SupShift;
use App\Http\Middleware\ahmed;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controllers\HasMiddleware;

class NozktController extends Controller implements HasMiddleware
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        return  view("nozkt.nozkt");
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {




        // $ddd= employees::find(1)->first()->free_d;

        // $startOfMonth = Carbon::now()->startOfMonth();
        // $endOfMonth = Carbon::now()->endOfMonth();

        // // حساب عدد أيام الإجازات في الشهر الحالي
        // $holidayCount = 0;

        // // التكرار عبر الأيام في الشهر الحالي
        // for ($day = $startOfMonth; $day <= $endOfMonth; $day->modify("+1 day")) {
        //     // الحصول على اسم اليوم باللغة العربية
        //     $dayName = Carbon::parse($day)->locale('ar')->dayName;

        //     if (in_array($dayName, $ddd)) {
        //         $holidayCount++;
        //     }
        // }
        // dd  ($holidayCount++);


        // dd($ddd);






        //  nozkt::truncate();
        count::truncate();

        $d = nozkt::all();
        $ste = Carbon::createFromFormat("Y-m-d", $request->searchs);
        $ete = Carbon::createFromFormat("Y-m-d", $request->searche);
        // $ste = Carbon::parse($request->searchs)->format("Y-m-d");
        // $ete = carbon::parse($request->searche)->format("Y-m-d");
        $dates = [];
        for ($date = $ste; $ste->lte($ete); $date->modify("+1 day")) {
            $dates[] = Carbon::parse($date)->format("Y-m-d");
        }

        // جلب بيانات الموظفين من قاعدة البيانات/////////////////////////////////
        $employeeData = [];
        $zkts = nozkt::whereBetween("date", [$request->searchs, $request->searche])->get();
        foreach ($zkts as $zkt) {
            $employeeData[$zkt->date][$zkt->numid] = $zkt;
        }

        // إدخال البيانات في قاعدة البيانات لكل موظف ولكل يوم
        foreach ($d as $emp) {
            foreach ($dates as $date) {
                if (isset($employeeData[$date][$emp->numid])) {
                    $checkIn = $employeeData[$date][$emp->in];
                    $checkOut = $employeeData[$date][$emp->out];
                    $breakin = $employeeData[$date][$emp->break_in];
                    $breakout = $employeeData[$date][$emp->break_out];
                    // تحقق من وجود كل من check_in و check_out قبل تحديد الحضور
                    if ($checkIn || $checkOut) {
                        $status = 0;
                    } else {
                        $status = 1;
                        // $checkIn = null;
                        // $checkOut = null;
                    }
                    // تحقق مما إذا كان السجل موجودًا بالفعل
                    $existingRecord = DB::table('nozkts')
                        ->where('numid', $emp->numid)
                        ->where('date', $date)
                        ->first();
                    // إدخال السجل في قاعدة البيانات فقط إذا لم يكن موجودًا
                    if (!$existingRecord) {
                        DB::table('nozkts')->updateOrInsert([
                            'numid' => $emp->numid,
                            'date' => $date,
                            'in' => $checkIn,
                            'out' => $checkOut,
                            'break_in' => $breakin,
                            'break_out' => $breakout,
                            'absent' => $status,
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now(),
                        ]);
                        //////////////////////////////////////////////////////////////////////
                        // dd($date);
                        // $empss = employees::where("num", $emp->numid)->first();
                        // if (in_array(Carbon::parse($date)->locale("ar")->dayName, $empss->free_d)) {
                        // if ($checkIn == null &&  $checkOut == null) {
                        // nozkt::where("numid", $emp->numid)->update([
                        // "absent" => "جازه رسميه"
                        // ]);
                        // } else {
                        // nozkt::find($emp->numid)->update([
                        // "absent" => "جازه رسميه"
                        // ]);;
                        // }
                        // }
                        /////////////////////////////////////////////////////////////////////////////////////////
                    }
                } else {
                    // تحقق مما إذا كان السجل موجودًا بالفعل كغياب
                    $existingRecord = DB::table('nozkts')
                        ->where('numid', $emp->numid)
                        ->where('date', $date)
                        ->first();
                    // إدخال السجل كغياب إذا لم يكن موجودًا
                    if (!$existingRecord) {
                        DB::table('nozkts')->updateOrInsert([
                            'numid' => $emp->numid,
                            'date' => $date,
                            'in' => null,
                            'out' => null,
                            'break_in' => null,
                            'break_out' => null,
                            'absent' => 1,
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now(),
                        ]);
                    }
                }
            }
        }
        // في حاله الشيفت المتداخل
        $records = DB::table('zkts')
            ->whereNull('in')
            ->orderBy('numid')
            ->whereNotNull('out')
            ->orderBy('date')
            ->get();

        foreach ($records as $record) {
            // الحصول على الصف اللي قبله
            $previousRecord = DB::table('zkts')
                ->where('numid', $record->numid)
                ->where('date', '<', $record->date)
                ->whereNotNull('in') // لازم يكون فيه تسجيل دخول
                ->whereNull('out') // لازم يكون فيه تسجيل دخول
                ->orderBy('date', 'desc')
                ->first();

            if ($previousRecord) {
                // تحديث الصف السابق بوضع تسجيل الخروج
                DB::table('zkts')
                    ->where('id', $previousRecord->id)
                    ->update(['out' => $record->out]);

                // حذف الصف اللي فيه check_in = NULL
                DB::table('zkts')
                    ->where('id', $record->id)
                    ->delete();
            }
        }
        //
        // dd("okk");
        ////// بكره
        $zkts = nozkt::whereBetween("date", [$request->searchs, $request->searche])->get();
        //  dd($zkts);
        foreach ($zkts as $zkkt) {
            $row[0] = $zkkt["numid"];
            $row[2] = $zkkt["date"];
            $row[3] = $zkkt["in"];
            $row[4] = $zkkt["out"];
            $row[5] = $zkkt["absent"];
            $row[6] = $zkkt["break_out"];
            $row[7] = $zkkt["break_in"];

            $zktRecord = \App\Models\zkt::where("numid", $row[0])->where("date", $row[2])->first();
            if ($zktRecord) {
                if ($row[3] === null && $zktRecord->in !== null) {
                    $row[3] = $zktRecord->in;
                } elseif ($row[3] !== null && $zktRecord->in !== null) {
                    $row[3] = min($row[3], $zktRecord->in);
                }

                if ($row[4] === null && $zktRecord->out !== null) {
                    $row[4] = $zktRecord->out;
                } elseif ($row[4] !== null && $zktRecord->out !== null) {
                    $row[4] = max($row[4], $zktRecord->out);
                }
            }

            // dd($zkkt);
            $rr = count::where("date", Carbon::parse($row[2])->format('y:m:d'))->where("numid", $row[0])->first();
            if (!$rr) {


                //  $existingRecordc = DB::table('counts')
                //   ->where('numid', $row[0])
                //
                //
                //   ->where('date', $row[2])
                //   ->first();
                //  if (!$existingRecordc) {

                $count = new count();



                $count->numid = $row[0];
                $count->break_out = $row[6];
                $count->break_in = $row[7];

                $empsf = employees::where("num", $row[0])->first();

                if ($row[3] === null) {
                    $exists_finger = forget_finger::where("employees_id", $empsf->id)->where("date", Carbon::parse($row[2])->toDateString())->exists();
                    if ($exists_finger) {
                        $row[3] = Carbon::parse(forget_finger::where("employees_id", $empsf->id)->where("date", Carbon::parse($row[2])->toDateString())->first()->in)->format('H:i:s');
                    }
                }


                if ($row[4] === null) {
                    $exists_finger_out = forget_finger::where("employees_id", $empsf->id)
                        ->where("date", Carbon::parse($row[2])->toDateString())->exists();
                    if ($exists_finger_out) {
                        $row[4] = Carbon::parse(forget_finger::where("employees_id", $empsf->id)
                            ->where("date", Carbon::parse($row[2])->toDateString())->first()->out)->format('H:i:s');
                    }
                }

                $innnn =  forget_finger::where("employees_id", $empsf->id)->where("date", Carbon::parse($row[2]))->exists();
                if ($innnn) {
                    $row[3] = Carbon::parse(forget_finger::where("employees_id", $empsf->id)->where("date", Carbon::parse($row[2])->toDateString())->first()->in)->format('H:i:s');
                }

                $ouuuut =  forget_finger::where("employees_id", $empsf->id)->where("date", Carbon::parse($row[2]))->exists();
                if ($ouuuut) {
                    $row[4] = Carbon::parse(forget_finger::where("employees_id", $empsf->id)->where("date", Carbon::parse($row[2])->toDateString())->first()->out)->format('H:i:s');
                }


                //   $em = employees::where("num", $row[0])->first();

                // dd   ($em->shift->free_d);
                //  dd   ($count->dayh = Carbon::parse(($row[3]))->diffInHours(Carbon::parse($row[4])));




                if ($row[3] === null) {
                    // dd("ok");
                    $count->in = null;
                } else {
                    $count->in = Carbon::parse($row[3])->format('H:i:s');
                }

                if ($row[4] === null) {
                    $count->out = null;
                    // dd("ok");
                } else {
                    $count->out = Carbon::parse($row[4])->format('H:i:s');
                }

                if ($zktRecord) {
                    $count->latitude_in = $zktRecord->latitude_in;
                    $count->longitude_in = $zktRecord->longitude_in;
                    $count->latitude_out = $zktRecord->latitude_out;
                    $count->longitude_out = $zktRecord->longitude_out;
                }
                // $count->out = Carbon::parse($row[4])->format('H:i:s');


                // if ( Carbon::parse($row[2])->locale("ar")->dayName == "الجمعة"){


                $count->date = Carbon::parse($row[2])->format('y:m:d');

                //  dd($row[3]);

                $emps = employees::where("num", $row[0])->first();

                //dd(Carbon::parse($row[4])->diffInHours( Carbon::parse($emps->shift->start_ot)));
                // $ddd=Carbon::parse("2:11:00")->diffInHours( Carbon::parse("4:00:00"));
                // dd($emps->name);
                //  $empsch= change_shift::where("employees_id",$emps->id)->first()->num;


                // /////////////////// تبديل شيفت للقسم

                // $ch_s_sh = head_sections::where("section_id", $emps->section_id)->first();

                // $exists_shift_section = head_sections::where("section_id", $emps->section_id)
                //     ->exists();


                // if ($exists_shift_section) {
                //     if (Carbon::parse($row[2])->between(Carbon::parse($ch_s_sh->date_start), Carbon::parse($ch_s_sh->date_end))) {
                //         $ch_s = head_sections::where("section_id", $emps->section_id)->first()->shift_id;
                //         //   dd($ch_s);
                //         ($emps->shift_id = $ch_s);
                //     }
                // }


                // /////////////////تبديل شيفت للموظف

                // $exists_shift = change_shift::where("employees_id", $emps->id)->where("date", Carbon::parse($row[2])->toDateString())->exists();

                // if (
                //     $exists_shift
                // ) {
                //     $shch = change_shift::where("employees_id", $emps->id)->first()->shift_id;
                //     $emps->shift_id = $shch;
                // }



                // /////////////////// تبديل شيفت للقسم
                $targetDate_sec = Carbon::parse($row[2])->toDateString();

                $exists_shift_section = head_sections::where("section_id", $emps->section_id)
                    ->whereDate("date_start", "<=", $targetDate_sec)
                    ->whereDate("date_end",   ">=", $targetDate_sec)
                    ->first();

                if (
                    $exists_shift_section
                ) {

                    $emps->shift_id = $exists_shift_section->shift_id;
                    $emps->shift->from = shift::find($exists_shift_section->shift_id)->from;
                    $emps->shift->to = shift::find($exists_shift_section->shift_id)->to;
                    $emps->shift->start_ot = shift::find($exists_shift_section->shift_id)->start_ot;
                    // $shch = head_sections::where("employees_id", $emps->id)->first()->shift_id;
                    // $emps->shift_id = $shch;
                }

                /////////////////تبديل شيفت للموظف
                // $exists_shift = change_shift::where("employees_id", $emps->id)->whereBetween("date", Carbon::parse($row[2])->toDateString())->exists();
                $targetDate = Carbon::parse($row[2])->toDateString();



                $ch_sh = change_shift::where("employees_id", $emps->id)
                    ->whereDate("date_start", "<=", $targetDate)
                    ->whereDate("date_end",   ">=", $targetDate)
                    ->first();

                if (
                    $ch_sh
                ) {

                    $emps->shift_id = $ch_sh->shift_id;
                    $emps->shift->from = shift::find($ch_sh->shift_id)->from;
                    $emps->shift->to = shift::find($ch_sh->shift_id)->to;
                    $emps->shift->start_ot = shift::find($ch_sh->shift_id)->start_ot;
                    // $shch = change_shift::where("employees_id", $emps->id)->first()->shift_id;
                    // $emps->shift_id = $shch;
                }

                ///////////////// تعديلات الشفتات الاستثنائية (مثلاً رمضان)
                $sup_sh = SupShift::where("shift_id", $emps->shift_id)
                    ->whereDate("start_date", "<=", $targetDate)
                    ->whereDate("end_date", ">=", $targetDate)
                    ->first();

                if ($sup_sh) {
                    $emps->shift_id = $sup_sh->override_shift_id;
                    $override = shift::find($sup_sh->override_shift_id);
                    if ($override) {
                        $emps->setRelation('shift', $override);
                    }
                }








                $count->kabdd = $emps->kabd;
                $count->shift_id = $emps->shift_id;
                $count->name = $emps->name;
                $count->employees_id = $emps->id;
                $count->in_duty = $emps->shift->from;
                $count->out_duty = $emps->shift->to;
                $count->section_id = $emps->section->id;



                $baseDate = $row[2];
                $shiftFromDt = Carbon::parse($baseDate . ' ' . $emps->shift->from);
                $shiftToDt = Carbon::parse($baseDate . ' ' . $emps->shift->to);
                $isOvernightShift = $shiftToDt->lt($shiftFromDt);
                $logicalCutoff = $shiftFromDt->copy()->subHours(4)->format('H:i:s');

                $timeInDt = $row[3] ? Carbon::parse($baseDate . ' ' . $row[3]) : null;
                $timeOutDt = $row[4] ? Carbon::parse($baseDate . ' ' . $row[4]) : null;

                if ($isOvernightShift) {
                    if ($timeInDt && Carbon::parse($row[3])->format('H:i:s') < $logicalCutoff) {
                        $timeInDt->addDay();
                    }
                    if ($timeOutDt && Carbon::parse($row[4])->format('H:i:s') < $logicalCutoff) {
                        $timeOutDt->addDay();
                    }
                }

                $tt = $shiftFromDt->copy()->addMinutes($emps->shift->late);
                if ($timeInDt === null) {
                    $count->early = null;
                } elseif ($timeInDt->gt($tt)) {
                    $lateMins = $shiftFromDt->diffInMinutes($timeInDt);
                    $count->early = Carbon::parse('00:00:00')->addMinutes($lateMins);
                } else {
                    $count->early = null;
                }

                $count->othours = 0;
                $count->ot = null;

                if ($timeOutDt !== null && $emps->shift && !empty($emps->shift->start_ot)) {
                    $startOtDt = Carbon::parse($baseDate . ' ' . $emps->shift->start_ot);
                    if ($isOvernightShift && $startOtDt->lt($shiftFromDt)) {
                        $startOtDt->addDay();
                    }

                    if ($timeOutDt->gt($startOtDt)) {
                        $otSeconds = $startOtDt->diffInSeconds($timeOutDt);
                        $count->othours = $otSeconds;
                        $count->ot = Carbon::parse($row[4])->format('H:i:s');
                    }
                }

                // الاوفر تايم بالطلب الشيف
                if ($count->othours > 0) {

                    $plzotx =  plzot::where("numid", $row[0])->where("date", $row[2])->exists();

                    if (!$plzotx) {
                        $plzott = nozkt::where("numid", $row[0])->where("date", $row[2])->first();



                        plzot::create([
                            "numid" => $row[0],
                            "name" => $empsf->name,
                            "in_duty" => $emps->shift->from,
                            "out_duty" => $emps->shift->to,
                            "date" => $row[2],
                            "in" => $row[3],
                            "out" => $row[4],
                            "othours" => $count->othours,
                            "count_ot" => round(
                                (((shift::where('id', $count->shift_id)->first()->ot * $count->othours) / 60) *
                                    $count->employees->counth) /
                                    60,
                                2,
                            ),

                            'latitude_in' => $plzott->latitude_in,
                            'longitude_in' => $plzott->longitude_in,
                            'latitude_out' => $plzott->latitude_out,
                            'longitude_out' => $plzott->longitude_out,

                            "status" => 0,
                        ]);
                    }
                }




                if ($timeInDt && $timeOutDt) {
                    if ($timeOutDt->lt($timeInDt)) {
                        $timeOutDt->addDay();
                    }
                    $count->dayh = $timeInDt->diffInSeconds($timeOutDt);
                } else {
                    $count->dayh = 0;
                }


                // الاوفر تايم بالطلب الشيف
                $plzot_status_0 = plzot::where("numid", $row[0])->where("date", $row[2])->where("status", 0)->exists();

                if ($plzot_status_0) {
                    $count->count_ot = 0;
                }

                ($plzot_status_1 = plzot::where("numid", $row[0])->where("date", $row[2])->where("status", 1)->exists());

                if ($plzot_status_1) {
                    $count->count_ot = round(
                        (((shift::where('id', $count->shift_id)->first()->ot * $count->othours) / 60) *
                            $count->employees->counth) /
                            60,
                        2,
                    );
                    // dd($count->count_ot);
                }



                //    $count->dayh = Carbon::parse(($row[3]))->diffInHours(Carbon::parse($row[4]));




                $break_dis_out = 0;
                $break_dis_in = 0;

                $break_out = Carbon::parse($emps->shift->from)->addMinutes($emps->shift->late);
                $break_in = Carbon::parse($emps->shift->from)->addMinutes($emps->shift->late1);

                if ($emps->shift->break_out > 0 && $emps->shift->break_out !== null && $count->break_out > 0) {
                    if (Carbon::parse($row[6])->lte($break_out)) {
                        //  $count->dis = $emps->shift->dism;
                        ($break_dis_out = $emps->shift->b_out_dis * $emps->counth / 60 *
                            Carbon::parse($row[6])->diffInMinutes(Carbon::parse($emps->shift->break_out)));
                    }
                }

                if ($emps->shift->break_in > 0 && $emps->shift->break_in !== null && $count->break_in > 0) {
                    if (Carbon::parse($row[7])->lte($break_in)) {
                        // $count->dis = $emps->shift->dism1;
                        ($break_dis_in = $emps->shift->b_in_dis * $emps->counth / 60 *
                            Carbon::parse($emps->shift->break_in)->diffInMinutes(Carbon::parse($row[7])));
                    }
                }


                // $shiftname = shift::where("id", $emps->shift_id)->first()->name;
                $shiftname = $emps->shift->name;
                // $count->shift = $shiftname;

                $minuteValue = ($emps->counth ?? 0) / 60;
                $count->dis = 0;

                if ($timeInDt && $timeInDt->gt($tt)) {
                    $lateMinutes = $shiftFromDt->diffInMinutes($timeInDt);

                    if ($emps->shift->late3 > 0 && $lateMinutes >= $emps->shift->late3) {
                        $count->dis = round($emps->shift->dism3 * $minuteValue, 2);
                    } elseif ($emps->shift->late2 > 0 && $lateMinutes >= $emps->shift->late2) {
                        $count->dis = round($emps->shift->dism2 * $minuteValue, 2);
                    } elseif ($emps->shift->late1 > 0 && $lateMinutes >= $emps->shift->late1) {
                        $count->dis = round($emps->shift->dism1 * $minuteValue, 2);
                    } elseif ($emps->shift->late > 0 && $lateMinutes >= $emps->shift->late) {
                        $count->dis = round($emps->shift->dism * $minuteValue, 2);
                    }
                }


                ////////////////////////////  دخول   الاستاذان      ////////////////////////


                $please_in = please::where("employees_id", $emps->id)->where("date", Carbon::parse($row[2])->toDateString())
                    ->where("in_out", 0)->exists();

                if ($please_in) {
                    $displz = please::where("employees_id", $emps->id)->first()->dis;
                    $dis_vplz = please::where("employees_id", $emps->id)->first()->dis_value;
                    // $plzin_out = please::where("employees_id", $emps->id)->first()->in_out;
                    $min = please::where("employees_id", $emps->id)->first()->minutes;
                    $lateplz = Carbon::parse($emps->shift->from)->addMinutes($min);
                    if ($displz > 0 &&  Carbon::parse($row[3])->gte($lateplz)) {
                        $count->dis = $displz * $emps->counth / 60 *
                            Carbon::parse($emps->shift->from)->diffInMinutes(Carbon::parse($row[3]));
                    }
                    if ($dis_vplz > 0 && Carbon::parse($row[3])->gte($lateplz)) {
                        $count->dis = $dis_vplz;
                    }
                    if ($displz == 0 && $dis_vplz == 0) {
                        $count->dis = 0;
                    }
                }


                // dd($count->dis);
                /////////////////////////////////////////////////////////////////////////////////////


                if ($row[3] === null) {
                    $count->dis = 0;
                }







                $count->disout = 0;
                if ($row[4] !== null && $emps->shift && !empty($emps->shift->to)) {
                    $baseDate = $row[2];
                    $shiftFromDt = Carbon::parse($baseDate . ' ' . $emps->shift->from);
                    $shiftToDt = Carbon::parse($baseDate . ' ' . $emps->shift->to);
                    $timeOutDt = Carbon::parse($baseDate . ' ' . $row[4]);

                    if ($shiftToDt->lt($shiftFromDt)) {
                        $shiftToDt->addDay();
                    }
                    if ($timeOutDt->lt($shiftFromDt)) {
                        $timeOutDt->addDay();
                    }

                    $bdryDt = $shiftToDt->copy()->subMinutes($emps->shift->bdry ?? 0);
                    if ($timeOutDt->lt($bdryDt)) {
                        $earlyMinutes = $timeOutDt->diffInMinutes($shiftToDt);
                        $count->disout = round(($emps->shift->disout ?? 0) * (($emps->counth ?? 0) / 60) * $earlyMinutes, 2);
                    }
                }

                ////////////////////////////   خروج  الاستاذان      ////////////////////////



                $please_out = please::where("employees_id", $emps->id)->where("date", Carbon::parse($row[2])->toDateString())
                    ->where("in_out", 1)->exists();
                if ($please_out) {
                    $displz = please::where("employees_id", $emps->id)->first()->dis;
                    $dis_vplz = please::where("employees_id", $emps->id)->first()->dis_value;
                    $min = please::where("employees_id", $emps->id)->first()->minutes;
                    $bdryplz = Carbon::parse($emps->shift->to)->subMinutes($min);
                    if ($displz > 0 &&  Carbon::parse($row[4])->lt($lateplz)) {
                        $count->disout = $displz * $emps->counth / 60 *
                            Carbon::parse($emps->shift->to)->diffInMinutes(Carbon::parse($row[4]));
                    }
                    if ($dis_vplz > 0 && Carbon::parse($row[4])->lt($bdryplz)) {
                        $count->disout = $dis_vplz;
                    }
                    if ($displz == 0 && $dis_vplz == 0) {
                        $count->disout = 0;
                    }
                }






                $vaction = vacation::where("employees_id", $emps->id)->where("date", Carbon::parse($row[2])->toDateString())
                    ->exists();
                if ($vaction) {
                    $count->absent = 0; //0
                } else {
                    if ($row[3] !== null || $row[4] !== null) {
                        $count->absent = 0; //0
                    } else {
                        $count->absent = 1;
                        $count->dis = 0;
                        $count->disout = 0;
                    }
                }



                if ($row[4] === null || $row[3] === null) {
                    $count->dayh = 0;
                }

                $em = employees::where("num", $row[0])->first();
                if (in_array(Carbon::parse($row[2])->locale("ar")->dayName, $em->free_d)) {
                    if ($row[3] == null &&  $row[4] == null) {
                        $count->friday = 0;
                        $count->absent = null; //0
                        $count->early = null;
                        $count->othours = 0;
                        $count->dis = 0;
                        $count->disout = 0;
                        $count->dayh = 0;
                    } else {
                        $count->friday = 1;
                        $count->absent = null; //0
                        $count->early = 0;
                        $count->othours = 0;
                        $count->dis = 0;
                        $count->disout = 0;
                    }
                }



                //// العيد

                $eid = eid::where("date", Carbon::parse($row[2])->toDateString())
                    ->exists();

                if ($eid) {
                    if ($row[3] == null &&  $row[4] == null) {
                        $count->friday = 0;
                        $count->absent = null; //0
                        $count->early = null;
                        $count->othours = 0;
                        $count->dis = 0;
                        $count->disout = 0;
                        $count->dayh = 0;
                        $count->eid = 1;
                        // dd("eid");
                    } else {
                        $count->friday = 1;
                        $count->absent = null; //0
                        $count->early = 0;
                        $count->othours = 0;
                        $count->dis = 0;
                        $count->disout = 0;
                        $count->eid = 1;
                        // dd("eid");
                    }
                }

                $count->absent_dis = round(
                    $count->absent * $count->employees->countday * shift::where('id', $count->shift_id)->first()->absent,
                    2,
                );

                $count->disout = $break_dis_out + $count->disout;
                $count->dis = $break_dis_in + $count->dis;

                if ($request->emps == 1) {
                    counts_emps::truncate();
                    counts_emps::create([
                        "numid" => $count->numid,
                        "name" => $count->name,
                        // "shift" => $count->shift,
                        "in_duty" => $count->in_duty,
                        "out_duty" => $count->out_duty,
                        "date" => $count->date,
                        "absent" => $count->absent,
                        "absent_dis" => $count->absent_dis,
                        "friday" => $count->friday,
                        "eid" => $count->eid,
                        "in" => $count->in,
                        "out" => $count->out,
                        "dis_loan" => $count->dis_loan,
                        "break_in" => $count->break_in,
                        "break_out" => $count->break_out,
                        "ot" => $count->ot,
                        "othours" => $count->othours,
                        "count_ot" => $count->count_ot,
                        "dayh" => $count->dayh,
                        "early" => $count->early,
                        "dis" => $count->dis,
                        "disout" => $count->disout,
                        "kabdd" => $count->kabdd,
                        "kabddis" => $count->kabddis,
                        "shift_id" => $count->shift_id,
                        "section_id" => $count->section_id,
                        "employees_id" => $count->employees_id,
                    ]);
                }





                $nozktex = nozkt::where("date", $row[2])->where("numid", $row[0])->exists();
                $ok_in = nozkt::where("date", $row[2])->where("numid", $row[0])->first()->ok_in;
                $ok_out = nozkt::where("date", $row[2])->where("numid", $row[0])->first()->ok_out;
                if ($nozktex) {
                    if ($ok_in == 0 && $ok_out == 0) {
                        $count->check_loction = 0;
                    } else {
                        $count->check_loction = 1;
                    }
                }



                $count->save();
            }
        }

        $loans = loan::get();
        foreach ($loans as $loan) {
            ($empid = $loan->employees->id);
            $start = Carbon::parse($loan->date_start)->firstOfMonth();
            $end = Carbon::parse($loan->date_end);
            while ($start->lte($end)) {

                count::where("date", $start->toDateString())->where("employees_id", $empid)->update([
                    "dis_loan" => $loan->mony_now,
                ]);
                $start->addMonth();
            }
        }
        // dd(34);

        session()->flash('Status');
        return redirect('/nozkt');
        // dd("dd");

    }

    public static function middleware()
    {
        return [
            ahmed::class
        ];
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $exists_in = nozkt::where("date", $request->date)->where("numid", $request->numid)->exists();

        if ($exists_in) {
            return response()->json([
                'success' => false,
                'message' => 'تم تسجيل دخول اليوم'
            ]);
        }

        $allowlat = 30.97537200;
        $allowlng = 31.16724700;

        $deltaLat = abs($allowlat - $request->input('latitude'));
        $deltaLng = abs($allowlng - $request->input('longitude'));

        if ($deltaLat <= 0.0009 && $deltaLng <= 0.0009) {
            $ok_in = 0;
        } else {
            $ok_in = 1;
        }






        try {
            nozkt::create([
                "numid" => $request->numid,
                "in" => $request->inn,
                "date" => $request->date,
                'latitude_in' => $request->input('latitude'),
                'longitude_in' => $request->input('longitude'),
                "name" => $request->name,
                "absent" => 0,
                "ok_in" => $ok_in,
            ]);

            zkt::updateOrCreate(
                ['numid' => $request->numid, 'date' => $request->date],
                [
                    'in' => $request->inn,
                    'name' => $request->name,
                    'absent' => 'حاضر',
                    'latitude_in' => $request->input('latitude'),
                    'longitude_in' => $request->input('longitude')
                ]
            );

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }



        $ip = '192.168.1';
        $ipm = '192.168.192';



        if (substr(request()->ip(), 0, 11) == substr('192.168.192', 0, 12)) {
            session()->flash("nolocation");
            return redirect('/index');
        } else {

            $in = nozkt::where("date", $request->date)->where("numid", $request->numid,)->first();
            if (!$in) {
                nozkt::updateOrcreate([
                    "numid" => $request->numid,
                    "in" => $request->in,
                    "date" => $request->date,
                    "name" => $request->name,
                    "absent" => 0,

                ]);
            } else {
                session()->flash("warning");
                return redirect('/index');
            }
            return back();
        }
    }

    public function updateout(Request $request)
    {



        $zktex = nozkt::where("numid", $request->numid)->count();

        // return response()->json(['success'=>true,
        //               'message' => $zktex]);




        if ($zktex >= 2) {
            //   return response()->json(['success'=>true,
            //   'message' =>  Carbon::now()->format('Y-m-d')]);
            $sss = nozkt::where("numid", $request->numid)->latest()->first();

            if ($request->input('date') !== $sss->date) {


                //           return response()->json(['success'=>true,
                //   'message' =>  $request->input('date')]);


                $previous  = nozkt::where("numid", $request->numid)->latest()->first();

                if ($previous && $previous->out  === null) {
                    $ss = nozkt::where("numid", $request->numid)->latest()->first();

                    nozkt::find($ss->id)->update([
                        "out" => employees::find(employees::where("num", $request->numid)->first()->id)->shift->to,
                        "no_out" => 1,

                    ]);


                    return response()->json([
                        'success' => true,
                        'message' => $ss->date . 'لم تبصم خروج امس'
                    ]);
                }
            }
        }


        // dd(($request->input('latitude')));



        $allowlat = 30.97537200;
        $allowlng = 31.16724700;

        $deltaLat = abs($allowlat - $request->input('latitude'));
        $deltaLng = abs($allowlng - $request->input('longitude'));

        if ($deltaLat <= 0.0009 && $deltaLng <= 0.0009) {
            $ok_out = 0;
        } else {
            $ok_out = 1;
        }




        $id = nozkt::where("numid", $request->numid)->latest()->first()->id;



        try {
            $nozkt = nozkt::find($id);
            $nozkt->update([
                "out" => $request->input('out'),
                'latitude_out' => $request->input('latitude'),
                'longitude_out' => $request->input('longitude'),
                'ok_out' => $ok_out
            ]);

            zkt::updateOrCreate(
                ['numid' => $nozkt->numid, 'date' => $nozkt->date],
                [
                    'out' => $request->input('out'),
                    'absent' => 'حاضر',
                    'latitude_out' => $request->input('latitude'),
                    'longitude_out' => $request->input('longitude')
                ]
            );

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }

        nozkt::find($id)->update([

            "out" => $request->out,


        ]);
        return back();
    }

    public function breakout(Request $request)
    {
        $id = nozkt::where("numid", $request->numid)->latest()->first()->id;
        $nozkt = nozkt::find($id);
        $nozkt->update([
            "break_out" => $request->break_out,
        ]);

        zkt::updateOrCreate(
            ['numid' => $nozkt->numid, 'date' => $nozkt->date],
            [
                'break_out' => $request->break_out,
                'latitude_break_out' => $request->input('latitude'),
                'longitude_break_out' => $request->input('longitude')
            ]
        );

        return response()->json(['success' => true]);
    }

    public function breakin(Request $request)
    {
        $id = nozkt::where("numid", $request->numid)->latest()->first()->id;
        $nozkt = nozkt::find($id);
        $nozkt->update([
            "break_in" => $request->break_in,
        ]);

        zkt::updateOrCreate(
            ['numid' => $nozkt->numid, 'date' => $nozkt->date],
            [
                'break_in' => $request->break_in,
                'latitude_break_in' => $request->input('latitude'),
                'longitude_break_in' => $request->input('longitude')
            ]
        );

        return response()->json(['success' => true]);
    }

    /**
     * Display the specified resource.
     */
    public function show(nozkt $nozkt)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(nozkt $nozkt)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, nozkt $nozkt)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(nozkt $nozkt)
    {
        //
    }
}
