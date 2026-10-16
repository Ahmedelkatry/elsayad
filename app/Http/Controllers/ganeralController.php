<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\hafz;
use App\Models\loan;
use App\Models\salf;
use App\Models\count;
use App\Models\shift;
use App\Models\disemp;
use App\Models\please;
use App\Models\employees;
use App\Models\purchases;
use Illuminate\Http\Request;

class ganeralController extends Controller
{
    public function index(Request $request)
    {

        $salfs = salf::whereBetween("date", [$request->start, $request->end])->get()->sum("salf");
        $hafzs = hafz::whereBetween("date", [$request->start, $request->end])->get()->sum("hafz");
        $diss = disemp::whereBetween("date", [$request->start, $request->end])->get()->sum("dis");
        // $loans = loan::whereBetween("date_start", [$request->start, $request->end])->get()->sum("mony");
        $counts_absent = count::whereBetween("date", [$request->start, $request->end])->get()->sum("absent_dis");
        $loans = count::whereBetween("date", [$request->start, $request->end])->get()->sum("dis_loan");
        $days_absent = count::whereBetween("date", [$request->start, $request->end])->where("absent", 1)->count();
        $counts_disin = count::whereBetween("date", [$request->start, $request->end])->get()->sum("dis");
        $counts_disout = count::whereBetween("date", [$request->start, $request->end])->get()->sum("disout");
        $days_free = count::whereBetween("date", [$request->start, $request->end])->get()->sum("friday"); // عدد ايام حضور الاجزات الرسميه

        $counts_ot = count::whereBetween("date", [$request->start, $request->end])->get()->sum("count_ot");
        $purchases = purchases::whereBetween("date", [$request->start, $request->end])->get()->sum("mony");


        $ganeral_dis = 0;
        $early_g = 0;
        $salary = 0;
        $salaryafter = 0;
        $count_free = 0;

        $exists_emp = employees::exists();
        if ($exists_emp) {

            $sysemp = employees::whereHas("count")->first()->sysemp;

            $count_free = employees::whereHas("count")->first()->friday *
                $days_free  * employees::whereHas("count")->first()->kabd / $sysemp;





            $ganeral_dis = $salfs  + $diss + $loans + $counts_absent + $counts_disin + $counts_disout + $purchases;


            $salary = employees::whereHas("count")->get()->sum("kabd");
            $salaryafter = $salary - $ganeral_dis + $hafzs + $counts_ot + $count_free;


            $times =   count::whereBetween("date", [$request->start, $request->end])->get("early");
            // dd(Carbon::parse(($times->early))->secondsSinceMidnight());
            $totalSeconds = 0;
            foreach ($times as $time) {

                if ($time->early === null) {
                    continue;
                } else {
                    $totalSeconds += (Carbon::parse(($time->early))->secondsSinceMidnight());
                }
            }
            $hours = floor($totalSeconds / 3600);
            $minutes = floor(($totalSeconds % 3600) / 60);
            $seconds = $totalSeconds % 60;

            ($early_g = sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds));
        }
        return view("counts.ganeral", compact(
            "salfs",
            "diss",
            "hafzs",
            "loans",
            "counts_absent",
            "counts_disin",
            "counts_disout",
            "ganeral_dis",
            "early_g",
            "salary",
            "salaryafter",
            "counts_ot",
            "count_free",
            "days_free",
            "days_absent",
            "purchases",
        ));
        // dd($salfs);
    }
}
