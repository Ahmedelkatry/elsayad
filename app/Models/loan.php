<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class loan extends Model
{
    use \App\Traits\RestrictsBySection, \App\Traits\NewestFirst;
    protected  $guarded = [];


    public function employees()
    {
        return $this->belongsTo(employees::class);
    }





    public function getmonthbakyAttribute()
    {

        if (Carbon::now()->gt(Carbon::parse($this->date_start))) {
            $noow = Carbon::now()->format("Y-m-d");

            //    $date_start=Carbon::create("19-12-2024") ;
            $date_start = Carbon::parse($this->date_start);
            $date_end = Carbon::parse($this->date_end);
            $daysPassed = $date_start->diff($noow)->m + 1;
            if (Carbon::now()->lt($date_end)){

                return

                //  round(
                    $this->month_now - $daysPassed;
                }else{
                    return 0;
                }
            // return

                //  round(
                // $this->month_now - $daysPassed;
            // * $this->mony_now
            //     -
            //      $daysPassed,

            // 2
            // );
        } else {
            return 0;
        }
    }


    public function getmonybakyAttribute()
    {
        if (Carbon::now()->gt(Carbon::parse($this->date_start))) {
            $noow = Carbon::now()->format("Y-m-d");

            //    $date_start=Carbon::create("19-12-2024") ;
            $date_end = Carbon::parse($this->date_end);
            $daysPassed = $date_end->diff($noow)->m + 1;
            if (Carbon::now()->lt($date_end)){

                return

                //  round(
                    $daysPassed * $this->mony_now;
                }else{
                    return 0;
                }
            // * $this->mony_now
            //     -
            //      $daysPassed,

            // 2
            // );
        } else {
            return 0;
        }
    }
}
