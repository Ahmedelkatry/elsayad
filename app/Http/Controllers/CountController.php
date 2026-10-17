<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\zkt;
use App\Models\hafz;
use App\Models\loan;
use App\Models\salf;
use App\Models\count;
use App\Models\shift;
use App\Models\disemp;
use App\Models\hafzeid;
use App\Models\section;
use App\Models\taminat;
use App\Models\vacation;
use App\Models\User;
use App\Models\nozkt;
use App\Models\employees;
use App\Models\ipzkt;
use Rats\Zkteco\Lib\ZKTeco;
use App\Models\purchases;
use App\Models\change_shift;
use Illuminate\Http\Request;
use App\Models\dis_transport;
use App\Http\Middleware\ahmed;
use App\Models\badel_transport;
use App\Imports\employeesImport;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Routing\Controllers\HasMiddleware;

class CountController extends Controller implements HasMiddleware
{


    public function index(Request $request)
    {
        if ($request->name == "") {
        } else {

            $emppps = employees::where("id", $request->name)->first();
            $workingDaysCount = 30;

            if ($emppps->choosesys == 1) {
                // شهر ثابت 30 يوم
                $workingDaysCount = 30;
            } elseif ($emppps->choosesys == 2) {
                // شهر متغير (أيام الشهر - الجمع)
                $holday = 0;
                if ($emppps->check == 1) {
                    $holidayCount = 0;
                    $monthStart = Carbon::parse($request->searchs)->startOfMonth();
                    $monthEnd = Carbon::parse($request->searchs)->endOfMonth();
                    for ($day = clone $monthStart; $day <= $monthEnd; $day->addDay()) {
                        $dayName = $day->locale('ar')->dayName;
                        if (in_array($dayName, $emppps->free_d ?? [])) {
                            $holidayCount++;
                        }
                    }
                    $holday = $holidayCount;
                }
                $workingDaysCount = Carbon::parse($request->searchs)->daysInMonth - $holday;
            } elseif ($emppps->choosesys == 3) {
                // تحديد مدة معينة من إعدادات الموظف
                $workingDaysCount = $emppps->sysemp > 0 ? $emppps->sysemp : 30;
            }
            
            // نستخدم القيمة المحسوبة للعرض فقط في التقرير ولا نقوم بتحديث القاعدة هنا
            $sysemp = $workingDaysCount;
            $days_Month = $workingDaysCount;
        }
        // dd(22);
        
        $cos = count::where("employees_id", $request->name)->whereBetween('date', array(date($request->searchs), date($request->searche)))
        ->get();
        


        $disemp =  disemp::where("employees_id", $request->name)->where(function ($q) use ($request) {
            $q->whereBetween('date', array($request->searchs, $request->searche));
        })->get();
        $salfs =  salf::where("employees_id", $request->name)->where(function ($q) use ($request) {
            $q->whereBetween('date', array($request->searchs, $request->searche));
        })->get();

        $hafz =  hafz::where("employees_id", $request->name)->where(function ($q) use ($request) {
            $q->whereBetween('date', array(($request->searchs), $request->searche));
        })->get();


        $purchas =  purchases::where("employees_id", $request->name)->where(function ($q) use ($request) {
            $q->whereBetween('date', array($request->searchs, $request->searche));
        })->get();
        // }
        // dd($purchases);

        $bet = 0;
        $countday = 0;
        $res = 0;
        $ree = 0;

        if ($request->name == "") {
            $empkabd = 0;
            $absent = 0;
            $disabsent = 0;
            $overtime = 0;
            $friday = 0;
            $sysemp = 0;
            $loan = 0;
            $dis_trans = 0;
            $badel_trans = 0;
            $countdd = 0;
            $id_emp = "";
            $emp_name = "";
            $taminat = 0;
            $purchases = 0;
            $badel_sys = 0;
            $eid = 0;
            $hafz_eid = 0;
            $early_new = 0;
            $absent_counts = 0;
            // $counts_absent = 0;

        } else {


            $badel_sys = 0;

            $emp_name = employees::where("id", $request->name)->first()->name;

            $empps = employees::where("id", $request->name)->first()->countday;
            $empp_shiftid = employees::where("id", $request->name)->first()->shift_id;
            $absent_shift = shift::where('id', $empp_shiftid)->first()->absent;



            $earlynew = count::where("employees_id", $request->name)
                ->whereBetween('date', array($request->searchs, $request->searche))->sum(DB::raw("TIME_TO_SEC(early)"));
            //  dd($early_count);
            //  ( $early_c=Carbon::parse($early_count)->secondsSinceMidnight() );
            ($early_new = gmdate("H:i:s", $earlynew));

            //بدل انتظام ميجا

            /*
            $early_count = count::where("employees_id", $request->name)
                ->whereBetween('date', array($request->searchs, $request->searche))->sum("early");

            //  ( $early_c=Carbon::parse($early_count)->secondsSinceMidnight() );
            ($early_c = Carbon::createFromTimestamp($early_count)->secondsSinceMidnight());


            $absent_count = count::where("employees_id", $request->name)
                ->whereBetween('date', array($request->searchs, $request->searche))->sum("absent");

            $shift_hdays = employees::find($request->name)->shift->workh;
            //  dd($shift_hdays*60*60*2);

            if (
                $early_c < $shift_hdays * 60 * 60 * 2 && $absent_count == 0 ||
                $absent_count == 1  && $early_count < $shift_hdays * 60 * 60

            ) {
                // dd("ok");
                $badel_sys = 2 * $empps;
            } else {
                $badel_sys = 0;
                // dd("no");
            }
*/

            //////////////عديه العيد

            $hafz_eid = 0;

            $hafz_eid_exists =   hafzeid::whereBetween('date', array(date($request->searchs), date($request->searche)))->exists();

            if ($hafz_eid_exists) {
                $hafz_eid = hafzeid::whereBetween('date', array(date($request->searchs), date($request->searche)))
                    ->first()->hafzeid * $empps;
                // dd($hafz_eid);
            };





            $purchases = 0;

            $purchasesex =   purchases::where("employees_id", $request->name)
                ->whereBetween('date', array(date($request->searchs), date($request->searche)))->exists();

            if ($purchasesex) {
                $purchases = purchases::where("employees_id", $request->name)
                    ->whereBetween('date', array(date($request->searchs), date($request->searche)))
                    ->first()->mony;
            };


            $taminat = 0;

            $taminats =   taminat::where("employees_id", $request->name)->exists();
            if ($taminats) {
                $taminat = taminat::where("employees_id", $request->name)->first()->mony;
            };




            $dis_trans = 0;

            $dis_transport =   dis_transport::where("employees_id", $request->name)->exists();
            if ($dis_transport) {
                $dis_trans = dis_transport::where("employees_id", $request->name)
                    ->first()->mony;
            };


            $badel_trans = 0;

            $badel_transport =   badel_transport::where("employees_id", $request->name)->exists();
            if ($badel_transport) {
                $badel_trans = badel_transport::where("employees_id", $request->name)->first()->mony;
            };

            $id_emp = $request->name;






            // dd($startOfMonth = Carbon::parse($request->searchs)->startOfMonth());
            //  dd($res = carbon::parse($request->searchs)->format("m"));
            $res = carbon::parse($request->searchs);
            $ree = carbon::parse($request->searche);
            ($bet = $res->diffInDays($ree) + 1);

            // تأكيد التعديل: إذا كان نظام الموظف "شهري ثابت" (1)، الحد الأقصى هو 30 يوماً
            $currentEmp = employees::where("id", $request->name)->first();
            if ($currentEmp && $currentEmp->choosesys == 1 && $bet > 30) {
                $bet = 30;
            }

            $count_null_absent = 0;
            if (employees::where("id", $request->name)->first()->check == 1) {

                ($count_null_absent = count::whereNull("absent")
                    ->where("employees_id", $request->name)
                    ->whereBetween('date', array(date($request->searchs), date($request->searche)))->get()->count());
            }

            ($countdd = $bet - $count_null_absent);


            $loan = 0;
            $loan_exstis = loan::where("employees_id", $request->name)->exists();
            if ($loan_exstis) {

                $date_start = Carbon::parse(loan::where("employees_id", $request->name)->first()->date_start);
                $date_end = Carbon::parse(loan::where("employees_id", $request->name)->first()->date_end);
                $loan_month = loan::where("employees_id", $request->name)->first()->month;

                if ($res->gte($date_start) && $ree->lte($date_end)) {
                    $loan = loan::where("employees_id", $request->name)
                        ->where("date_start", "<=", $res)
                        ->where("date_end", ">=", $ree)->get()->sum("mony_now");
                }
            }





            $empkabd = employees::when($request->name, function ($i) use ($request) {
                return $i->where("id", $request->name);
            })->first()->kabd;

            $countday = $sysemp > 0 ? ($empkabd / $sysemp) : 0;
            // $absent = $cos->sum("absent");
            $absent = $cos->where("absent", 1)->count();

            //////////////////
            // $disabsent = $absent * shift::where("id", employees::when($request->name, function ($i) use ($request) {
            //     return $i->where("id", $request->name);
            // })->first()->shift_id)->first()->absent * $empkabd / $sysemp;

            ($disabsent = $cos->sum("absent_dis"));
            //////////////////////


            $ovt = $cos->sum("othours");
            // $overtime = shift::where("id", employees::where("id", $request->name)->first()->shift_id)->first()->ot *
            //     $ovt / 60 / 60 * employees::where("id", $request->name)->first()->kabd / $sysemp / shift::where("id", employees::where("id", $request->name)->first()->shift_id)->first()->workh;
            $overtime = $cos->sum("count_ot");
            // $friday = shift::where("id", employees::where("id", $request->name)->first()->shift_id)->first()->friday *
            $friday = employees::where("id", $request->name)->first()->friday *
                $cos->sum("friday")  * employees::where("id", $request->name)->first()->kabd / $sysemp;
        }





        $employees = employees::get();

        $disin = $cos->sum("dis");
        $disout = $cos->sum("disout");

        $disdis = $disemp->sum("dis");
        $dissalf = $salfs->sum("salf");
        $hafzs = $hafz->sum("hafz");
        $dayss = $cos->sum("dayh");
        $eid = $cos->sum("eid");


        $absent_counts = 0;
        $counts_absent = $cos->isNotEmpty();
        if ($counts_absent) {
            $absent_counts = $cos->first()->absent * $countday * $absent_shift;
            // $absent_counts=$cos->first()->absent * $countday * $absent_shift;

        }


        $fri = $cos->sum("friday");
        $othours = $cos->sum("othours");


        $count_vacation = vacation::where("employees_id", $request->name)->get()->count();


        ($afterdis =  $disin + $disout + $disdis + $dissalf + $disabsent + $loan + $dis_trans + $badel_trans + $taminat + $purchases);

        $afterot =
            $countday *
            $sysemp -
            $afterdis
            // $disin - $loan - $disout - $disdis - $dissalf - $badel_trans - $dis_trans - $disabsent
            + $overtime + $friday + $hafzs + $badel_sys + $hafz_eid;

        $salary_days =
            ($countday)
            *
            ($countdd)
            -
            ($afterdis)
            +
            $overtime + $friday + $hafzs + $badel_sys + $hafz_eid;

        // تأكيد نهائي قبل العرض: إذا كان نظام الموظف "شهري ثابت" (1)، الحد الأقصى للأيام هو 30
        $finalEmpCheck = employees::find($request->name);
        if ($finalEmpCheck && (int)$finalEmpCheck->choosesys === 1 && $bet > 30) {
            $bet = 30;
        }

        if ($request->name == "") {
        }
        return view("counts.counts", compact(
            "cos",
            "disemp",
            "employees",
            "salfs",
            "disin",
            "disout",
            "absent",
            "disdis",
            "dissalf",
            "empkabd",
            "afterdis",
            "disabsent",
            "overtime",
            "afterot",
            "dayss",
            "friday",
            "fri",
            "othours",
            "hafzs",
            "hafz",
            "ree",
            "res",
            "bet",
            "countday",
            "sysemp",
            "loan",
            "dis_trans",
            "badel_trans",
            "id_emp",
            "count_vacation",
            "taminat",
            "purchases",
            "badel_sys",
            "emp_name",
            "eid",
            "hafz_eid",
            "early_new",
            "salary_days",
            "purchas",
            "absent_counts",

        ));
    }


    public function create()
    {
        //
    }
    public static function middleware()
    {
        return [
            ahmed::class
        ];
    }


    public function store(Request $request)
    {

        count::truncate();
        Excel::import(new employeesImport, $request->file('file'));
        // Excel::import(new employeesImport, $request->file('file', \Maatwebsite\Excel\Excel::XLS));
        return back();
    }

    /**
     * Display the specified resource.
     */
    public function show()
    {
        $emps = employees::get();
        $zktcounts = count::get();
        return view("zktcount.zktcount", compact("zktcounts", "emps"));
    }



    public function edit(count $count)
    {
        //
    }



    public function update(Request $request, $id)
    {




        $emp = count::where("id", $id)->first();
        $empid = count::where("id", $id)->first()->employees_id;
        $ot = employees::find($empid)->shift->start_ot;
        $time_in_up = $request->in ? Carbon::parse($request->in) : null;
        $time_out_up = $request->out ? Carbon::parse($request->out) : null;
        $dayh = 0;
        if ($time_in_up && $time_out_up) {
            if ($time_out_up->lt($time_in_up)) {
                $time_out_up->addDay();
            }
            $dayh = $time_in_up->diffInSeconds($time_out_up);
        }
        // $othourss = Carbon::parse($ot)->diffInSeconds(Carbon::parse($request->out));
        // dd(Carbon::parse($dayh));

        $othours = Carbon::parse($request->othours)->secondsSinceMidnight();
        count::find($id)->update([
            "in" => $request->in,
            "out" => $request->out,
            "dis" => $request->dis,
            "disout" => $request->disout,
            "early" => $request->early,
            "dayh" => $dayh,
            "othours" => $othours,
        ]);


        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        count::find($id)->delete();
        return back();
    }





    public function store1(Request $request)
    {
        // $request->validate([
        //     "name" => "required",
        //     "date" => "required",
        //     "in" => "required",
        //     "out" => "required",
        //     //"dis"=> "required",
        //     //"disout" => "required",
        //     // "early"=> "required",
        //     // "dayh" => "required",
        //     // "othours" => "required",

        // ]);


        $emp = employees::where("name", $request->name)->first();
        $empid = $emp->id;

        $baseDate = $request->date;
        $shiftFromDt = $emp->shift ? Carbon::parse($baseDate . ' ' . $emp->shift->from) : null;
        $shiftToDt = $emp->shift ? Carbon::parse($baseDate . ' ' . $emp->shift->to) : null;
        $isOvernightShift = ($shiftFromDt && $shiftToDt) ? $shiftToDt->lt($shiftFromDt) : false;
        $logicalCutoff = $shiftFromDt ? $shiftFromDt->copy()->subHours(4)->format('H:i:s') : '12:00:00';

        $time_in_st = $request->in ? Carbon::parse($baseDate . ' ' . $request->in) : null;
        $time_out_st = $request->out ? Carbon::parse($baseDate . ' ' . $request->out) : null;

        if ($isOvernightShift) {
            if ($time_in_st && Carbon::parse($request->in)->format('H:i:s') < $logicalCutoff) {
                $time_in_st->addDay();
            }
            if ($time_out_st && Carbon::parse($request->out)->format('H:i:s') < $logicalCutoff) {
                $time_out_st->addDay();
            }
        }

        ///////////خصم الدخول
        $minuteValue = ($emp->counth ?? 0) / 60;
        $disinn = 0;
        $lateThreshold = $shiftFromDt ? $shiftFromDt->copy()->addMinutes($emp->shift->late) : null;

        if ($time_in_st && $lateThreshold && $time_in_st->gt($lateThreshold)) {
            $lateMinutes = $shiftFromDt->diffInMinutes($time_in_st);

            if ($emp->shift->late3 > 0 && $lateMinutes >= $emp->shift->late3) {
                $disinn = round($emp->shift->dism3 * $minuteValue, 2);
            } elseif ($emp->shift->late2 > 0 && $lateMinutes >= $emp->shift->late2) {
                $disinn = round($emp->shift->dism2 * $minuteValue, 2);
            } elseif ($emp->shift->late1 > 0 && $lateMinutes >= $emp->shift->late1) {
                $disinn = round($emp->shift->dism1 * $minuteValue, 2);
            } elseif ($emp->shift->late > 0 && $lateMinutes >= $emp->shift->late) {
                $disinn = round($emp->shift->dism * $minuteValue, 2);
            }
        }

        // if($disinn = null || $disinn = 0 ){$disinn = 0;}
        // elseif (Carbon::parse($row[3])->lte($late3)) {
        // $count->dis = 0;
        // }







        //   Carbon::parse($emp->shift->from)->diffInMinutes(Carbon::parse($row[3]));
        //  dd($emp->shift->late);
        // dd(Carbon::parse($emp->shift->from)->addMinutes($emp->shift->late));
        //  if ($request->in === null || $request->in === 0) {
        // $disinn = 0;
        //  } elseif (Carbon::parse($request->in)->gte($late3)) {
        // $disinn = $emp->countday;
        // $disinn = $emp->shift->dism3 * $emp->counth;
        //  }
        // elseif (Carbon::parse($row[3])->lte($late3)) {
        // $count->dis = 0;
        // }
        /////////////////////////////
        //  elseif (Carbon::parse($request->in)->gte($late2)) {
        // $disinn = $emp->countday / 2;
        // $disinn = $emp->shift->dism2 * $emp->counth;
        //  }
        //  elseif (Carbon::parse($row[3])->lte($late2)) {
        //    $count->dis = 0;
        // }
        ////////////////////////////////////
        //  elseif (Carbon::parse($request->in)->gte($late1)) {
        //    $count->dis = $emp->shift->dism1;
        //    $disinn = $emp->shift->dism1 * $emp->counth;
        //  }
        // elseif (Carbon::parse($row[3])->lte($late1)) {
        //    $count->dis = 0;
        // }
        ////////////////////////////
        //  elseif (Carbon::parse($request->in)->gte($late)) {
        //  $count->dis = $emp->shift->dism;
        //    $disinn = $emp->shift->dism * $emp->counth / 60 * Carbon::parse($emp->shift->from)->diffInMinutes(Carbon::parse($request->in));
        //  }
        //  elseif (Carbon::parse($request->in)->lte($late)) {
        // $disinn= 0;
        //  }



        // $late = Carbon::parse($emp->shift->from)->addMinutes($emp->shift->late);

        // if ($request->in === null || $request->in === 0) {
        // $disinn = 0;
        // } elseif (Carbon::parse($request->in)->gt($late)) {
        // $disinn = $emp->shift->dism;
        // } elseif (Carbon::parse($request->in)->lte($late)) {
        // $disinn =  0;
        // };
        ///// خصم الخروج

        ///// خصم الخروج
        $disoutt = 0;
        $othourss = 0;

        if ($time_out_st && $emp->shift) {
            $startOtDt = Carbon::parse($baseDate . ' ' . $emp->shift->start_ot);
            if ($isOvernightShift) {
                if ($shiftToDt->lt($shiftFromDt)) {
                    $shiftToDt->addDay();
                }
                if ($startOtDt->lt($shiftFromDt)) {
                    $startOtDt->addDay();
                }
            }

            // خروج مبكر
            $bdryDt = $shiftToDt->copy()->subMinutes($emp->shift->bdry ?? 0);
            if ($time_out_st->lt($bdryDt)) {
                $earlyMinutes = $time_out_st->diffInMinutes($shiftToDt);
                $disoutt = round(($emp->shift->disout ?? 0) * (($emp->counth ?? 0) / 60) * $earlyMinutes, 2);
            }

            // أوفر تايم
            if ($time_out_st->gt($startOtDt)) {
                $othourss = $startOtDt->diffInSeconds($time_out_st);
            }
        }

        /////////////// عدد ساعات العمل
        $dayh = 0;
        if ($time_in_st && $time_out_st) {
            if ($time_out_st->lt($time_in_st)) {
                $time_out_st->addDay();
            }
            $dayh = $time_in_st->diffInSeconds($time_out_st);
        }

        count::create([
            "shift_id" => $emp->shift->id,
            "employees_id" => $emp->id,
            "section_id" => $emp->section_id,
            "in_duty" => $emp->shift->from,
            "out_duty" => $emp->shift->to,
            "shift" => $emp->shift->name,
            "name" => $request->name,
            "date" => $request->date,
            "in" => $request->in,
            "out" => $request->out,
            // "dis" => $request->dis,
            "dis" => $disinn,
            // "disout" => $request->disout,
            "disout" => $disoutt,
            // "early" => $request->early,
            "early" => (Carbon::parse(date("H-i-s", strtotime("00:00:00")))->addMinutes(Carbon::parse($emp->shift->from)->diffInMinutes(Carbon::parse($request->in)))),
            // "dayh" => $request->dayh,
            "dayh" => $dayh,
            "othours" => $othourss,
            // "othours" => $request->othours,
            "absent" => $request->absent,
        ]);
        return back();
    }

    public function countr(Request $request)
    {
        $report_data = [];
        $sections = section::get();
        $res = Carbon::parse($request->searchs);
        $ree = Carbon::parse($request->searche);
        $bet = $res->diffInDays($ree) + 1;
        $empsys_days_search = 0;
        $countdd = 0;

        if ($request->name && $request->searchs && $request->searche) {
            $go = count::whereHas("employees", function ($q) use ($request) {
                if ($request->name !== 'all') {
                    if (is_array($request->name)) {
                        $q->whereIn("section_id", $request->name);
                    } else {
                        $q->where("section_id", $request->name);
                    }
                }
            })->whereBetween('date', array($request->searchs, $request->searche))
              ->with("employees")
              ->get()
              ->groupBy("employees.name");

            $count_null_absent = count::whereNull("absent")
                ->whereBetween('date', array($request->searchs, $request->searche))
                ->count();
            $empsys_days_search = $bet - $count_null_absent;

            $emp_with_count = employees::whereHas("countRelation", function ($q) use ($request) {
                if ($request->name !== 'all') {
                    $q->where("section_id", $request->name);
                }
            })->first();
            if ($emp_with_count) {
                $countdd = $emp_with_count->sysemp;
            }

            foreach ($go as $emp_name => $counts) {
                $employee = $counts->first()->employees;
                if (!$employee) continue;
                $emp_id = $employee->id;

                // Calculate working days count (sysfull) for this employee to find daily rate (countday)
                $workingDaysCount = 30;
                if ($employee->choosesys == 1) {
                    $workingDaysCount = 30;
                } elseif ($employee->choosesys == 2) {
                    $holday = 0;
                    if ($employee->check == 1) {
                        $holidayCount = 0;
                        $monthStart = Carbon::parse($request->searchs)->startOfMonth();
                        $monthEnd = Carbon::parse($request->searchs)->endOfMonth();
                        for ($day = clone $monthStart; $day <= $monthEnd; $day->addDay()) {
                            $dayName = $day->locale('ar')->dayName;
                            if (in_array($dayName, $employee->free_d ?? [])) {
                                $holidayCount++;
                            }
                        }
                        $holday = $holidayCount;
                    }
                    $workingDaysCount = Carbon::parse($request->searchs)->daysInMonth - $holday;
                } elseif ($employee->choosesys == 3) {
                    $workingDaysCount = $employee->sysemp > 0 ? $employee->sysemp : 30;
                }
                
                $sysfull = $workingDaysCount;
                $countday = $sysfull > 0 ? ($employee->kabd / $sysfull) : 0;

                $in = round($counts->sum('dis'), 2);
                $out = round($counts->sum('disout'), 2);
                $absent_days = $counts->where('absent', 1)->count();
                
                $sysemp = $employee->sysemp;
                $absent_multiplier = $employee->shift ? $employee->shift->absent : 1;
                $absentt = $absent_days * $countday * $absent_multiplier;

                $outdis = disemp::where('employees_id', $emp_id)->whereBetween('date', [$res, $ree])->sum('dis');
                $salfdis = salf::where('employees_id', $emp_id)->whereBetween('date', [$res, $ree])->sum('salf');
                $loan = loan::where('employees_id', $emp_id)->where('date_start', '<=', $res)->where('date_end', '>=', $res)->sum('mony_now');
                $dis_transport = dis_transport::where('employees_id', $emp_id)->sum('mony');
                $badel_transport = badel_transport::where('employees_id', $emp_id)->sum('mony');
                $taminat = taminat::where('employees_id', $emp_id)->sum('mony');
                $purchases = purchases::where('employees_id', $emp_id)->whereBetween('date', [$res, $ree])->sum('mony');

                $hourss = $counts->sum('dayh');
                $hours = floor($hourss / 3600);
                $minutes = floor(($hourss % 3600) / 60);
                $seconds = $hourss % 60;
                $times = sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);

                $kabdd = employees::where('name', $emp_name)->sum('kabd');

                $vacation = vacation::where('employees_id', $emp_id)->count();
                
                $friday_attendance = $counts->sum('friday');
                $otfree = round($friday_attendance * $employee->friday * $countday, 2);

                $othourss = $counts->sum('othours');
                $ot = round($counts->sum('count_ot'), 2);

                $hafz = hafz::where('employees_id', $emp_id)->whereBetween('date', [$res, $ree])->sum('hafz');
                $eid_days = $counts->sum('eid');

                $hafz_eid_exists = hafzeid::whereBetween('date', [$res, $ree])->exists();
                $hafz_eid = 0;
                if ($hafz_eid_exists) {
                    $hafz_eid = hafzeid::whereBetween('date', [$res, $ree])->first()->hafzeid * $countday;
                }

                $net_salary = ($countday * $sysfull) + $hafz + $hafz_eid + $otfree + $ot + $badel_transport
                              - $in - $out - $absentt - $salfdis - $outdis 
                              - $dis_transport - $taminat - $purchases - $loan;

                $report_data[] = [
                    'emp_name' => $emp_name,
                    'in' => $in,
                    'out' => $out,
                    'absent_days' => $absent_days,
                    'absentt' => $absentt,
                    'sysemp' => $sysemp,
                    'outdis' => $outdis,
                    'salfdis' => $salfdis,
                    'loan' => $loan,
                    'dis_transport' => $dis_transport,
                    'badel_transport' => $badel_transport,
                    'taminat' => $taminat,
                    'purchases' => $purchases,
                    'times' => $times,
                    'kabdd' => $kabdd,
                    'countday' => $countday,
                    'vacation' => $vacation,
                    'friday_attendance' => $friday_attendance,
                    'otfree' => $otfree,
                    'othours_formatted' => Carbon::parse($othourss)->format('H:i:s'),
                    'ot' => $ot,
                    'hafz' => $hafz,
                    'eid_days' => $eid_days,
                    'hafz_eid' => $hafz_eid,
                    'net_salary' => $net_salary
                ];
            }
        }

        return view("counts.countr", compact(
            "report_data",
            "sections",
            "res",
            "ree",
            "bet",
            "countdd",
            "empsys_days_search"
        ));
    }
    public function bulk_delete(Request $request)
    {
        if ($request->ids) {
            $emps = employees::whereIn('id', $request->ids)->get();
            $nums = $emps->pluck('num')->toArray();
            $stringNums = array_map('strval', $nums);

            // 1. تنظيف جداول مزامنة الأجهزة للموظفين المطلوب حذفهم
            \Illuminate\Support\Facades\DB::table('zkt_device_sync')->whereIn('emp_num', $stringNums)->delete();
            \Illuminate\Support\Facades\DB::table('zkt_device_deletions')->whereIn('emp_num', $stringNums)->delete();

            // 2. تسجيل أمر الحذف لوسيط الفروع (zk_mediator) لكافة الماكينات
            \App\Models\ZktDeletedUser::whereIn('num', $stringNums)->delete();
            foreach ($emps as $emp) {
                if ($emp->num) {
                    \App\Models\ZktDeletedUser::create([
                        'num'        => (string)$emp->num,
                        'name'       => $emp->name,
                        'device_key' => null, // لكل الأجهزة والفروع
                        'executed'   => 0,
                    ]);
                }
            }

            // 3. إرسال أمر الحذف لكافة أجهزة ADMS السحابية
            $admsDevices = \App\Models\ipzkt::whereNotNull('sn')->where('sn', '!=', '')->get();
            if ($admsDevices->isNotEmpty()) {
                foreach ($nums as $num) {
                    if ($num) {
                        foreach ($admsDevices as $device) {
                            \App\Models\ZktCommand::create([
                                'sn'      => $device->sn,
                                'command' => "DATA DELETE userinfo PIN={$num}",
                            ]);
                        }
                    }
                }
            }

            // 4. حذف الموظفين من أجهزة البصمة المتاحة على الشبكة المحلية مباشرة (مع مهلة سريعة)
            $allIps = ipzkt::pluck('ip')->toArray();
            foreach ($allIps as $ip) {
                if (filter_var($ip, FILTER_VALIDATE_IP)) {
                    $sock = @fsockopen($ip, 4370, $errno, $errstr, 0.5);
                    if ($sock) {
                        fclose($sock);
                        try {
                            $zk = new ZKTeco($ip);
                            if (@$zk->connect()) {
                                foreach ($nums as $num) {
                                    @$zk->removeUser((int)$num);
                                    \Illuminate\Support\Facades\DB::table('zkt_device_deletions')->updateOrInsert(
                                        ['device_key' => $ip, 'emp_num' => (string)$num],
                                        ['deleted_at' => now(), 'updated_at' => now()]
                                    );
                                }
                                @$zk->disconnect();
                            }
                        } catch (\Throwable $e) {}
                    }
                }
            }

            // حذف سجلات البصمة
            zkt::whereIn('numid', $nums)->delete();
            nozkt::whereIn('numid', $nums)->delete();

            // حذف حسابات المستخدمين المرتبطة
            $emails = $emps->whereNotNull('email')->pluck('email')->toArray();
            if (!empty($emails)) {
                User::whereIn('email', $emails)->delete();
            }

            employees::whereIn('id', $request->ids)->delete();
            return back()->with('delete', 'تم حذف الموظفين المختارين بنجاح وجاري مزامنة حذفهم من أجهزة البصمة.');
        }
        return back()->with('error', 'يرجى اختيار موظفين للحذف');
    }
}
