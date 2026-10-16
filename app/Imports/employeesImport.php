<?php

namespace App\Imports;

use Carbon\Carbon;
use App\Models\eid;
use App\Models\salf;
use App\Models\count;
use App\Models\shift;
use App\Models\disemp;
use App\Models\please;
use App\Models\vacation;
use App\Models\employees;
use App\Models\change_shift;
use App\Models\forget_finger;
use App\Models\head_sections;
use App\Models\SupShift;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\Http\Controllers\ChangeShiftController;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class employeesImport implements ToCollection, ToModel
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function collection(Collection $rows)
    {



        //  dd ($rows);
        // return new employees([
        //     "num"=> $rows[0],
        //     "name"=>$rows[1],
        //     "age"=>$rows,
        //     "in"=>$rows,
        //     "out"=>$rows,
        //     "kabd"=>$rows
        // ]);
    }
    private $i = 0;



    public function model(array $row)
    {
        // dd ($row);
        //employees::create([
        //     "num"=>$row[0],
        //    "name"=>$row[1],
        //     "age"=>$row[2] ,
        //      "in"=>$row[3] ,
        //     "out"=>$row[4] ,
        //    "kabd"=>$row[5]
        //]);
        //       $this->i++;
        //if($this->i>1){
        //      dd($row[2]);
        //    }
        //   dd(date('H:i:s',strtotime($row[4])));
        // dd(Carbon::parse($row[4]));







        $this->i++;
        if ($this->i > 1) {


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


                $empsf = employees::where("num", $row[0])->first();
                if ($row[3] === null) {
                    $exists_finger = forget_finger::where("employees_id", $empsf->id)->where("date", Carbon::parse($row[2])->toDateString())->exists();
                    if ($exists_finger) {
                        $row[3] = Carbon::parse(forget_finger::where("employees_id", $empsf->id)->first()->in)->format('H:i:s');
                    }
                }


                if ($row[4] === null) {
                    $exists_finger_out = forget_finger::where("employees_id", $empsf->id)->where("date", Carbon::parse($row[2])->toDateString())->exists();
                    if ($exists_finger_out) {
                        $row[4] = Carbon::parse(forget_finger::where("employees_id", $empsf->id)->first()->out)->format('H:i:s');
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
                // $count->out = Carbon::parse($row[4])->format('H:i:s');


                // if ( Carbon::parse($row[2])->locale("ar")->dayName == "الجمعة"){


                $count->date = Carbon::parse($row[2])->format('y:m:d');

                //  dd($row[3]);

                $emps = employees::where("num", $row[0])->first();

                //dd(Carbon::parse($row[4])->diffInHours( Carbon::parse($emps->shift->start_ot)));
                // $ddd=Carbon::parse("2:11:00")->diffInHours( Carbon::parse("4:00:00"));
                // dd($emps->name);
                //  $empsch= change_shift::where("employees_id",$emps->id)->first()->num;


                /////////////////// تبديل شيفت للقسم

                $ch_s_sh = head_sections::where("section_id", $emps->section_id)->first();

                $exists_shift_section = head_sections::where("section_id", $emps->section_id)
                    ->exists();
                // dd($exists_shift_section);

                if (Carbon::parse($row[2])->between(Carbon::parse($ch_s_sh->date_start), Carbon::parse($ch_s_sh->date_end))) {
                    if ($exists_shift_section) {
                        $ch_s = head_sections::where("section_id", $emps->section_id)->first()->shift_id;
                        //   dd($ch_s);
                        ($emps->shift_id = $ch_s);
                    }
                }


                /////////////////تبديل شيفت للموظف

                $exists_shift = change_shift::where("employees_id", $emps->id)->where("date", Carbon::parse($row[2])->toDateString())->exists();



                if (
                    $exists_shift
                ) {
                    $shch = change_shift::where("employees_id", $emps->id)->first()->shift_id;
                    $emps->shift_id = $shch;
                }

                ///////////////// تعديلات الشفتات الاستثنائية (مثلاً رمضان)
                $targetDate = Carbon::parse($row[2])->toDateString();
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




                // $ddd = Carbon::create("2024-11-08")->toDateString();
                // if ($ddd == Carbon::now()->toDateString()) {
                // }
                //  dd($emps->shift_id);



                $count->kabdd = $emps->kabd;
                $count->shift_id = $emps->shift_id;
                $count->name = $emps->name;
                $count->employees_id = $emps->id;
                $count->in_duty = $emps->shift->from;
                $count->out_duty = $emps->shift->to;
                $count->section_id = $emps->section->id;




                //  $ee = Carbon::parse(date("H-i-s", strtotime("00:00:00")))->addMinutes($emps->shift->late)->format("H:i:s");
                $tt = Carbon::parse($emps->shift->from)->addMinutes($emps->shift->late);
                // dd($tt);
                // dd(Carbon::parse($emps->shift->from)->diffInHours(Carbon::parse($row[3])));
                if ($row[3] === null) {
                    $count->early === null;
                } elseif (Carbon::parse($row[3])->gt($tt)) {
                    $count->early = (Carbon::parse(date("H-i-s", strtotime("00:00:00")))->addMinutes(Carbon::parse($emps->shift->from)->diffInMinutes(Carbon::parse($row[3]))));
                }

                $count->othours = 0;
                $count->ot = null;

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

                $count->dayh = 0;
                if ($timeInDt && $timeOutDt) {
                    if ($timeOutDt->lt($timeInDt)) {
                        $timeOutDt->addDay();
                    }
                    $count->dayh = $timeInDt->diffInSeconds($timeOutDt);
                }

                // $shiftname = shift::where("id", $emps->shift_id)->first()->name;
                $shiftname = $emps->shift->name;
                $count->shift = $shiftname;

                $minuteValue = ($emps->counth ?? 0) / 60;
                $count->dis = 0;
                $lateThreshold = $shiftFromDt->copy()->addMinutes($emps->shift->late);

                if ($timeInDt && $timeInDt->gt($lateThreshold)) {
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

                //  $dateplz = please::where("employees_id", $emps->id)->first()->date;

                // if (
                // please::where("employees_id", $emps->id)->first()->employees_id === $emps->id
                // &&  Carbon::parse($dateplz)->toDateString() == Carbon::parse($row[2])->toDateString() && $plzin_out == 0
                // )

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
                    $count->absent = 0;
                } else {
                    if ($row[3] !== null || $row[4] !== null) {
                        $count->absent = 0;
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
                        $count->absent = null;
                        $count->early = null;
                        $count->othours = 0;
                        $count->dis = 0;
                        $count->disout = 0;
                        $count->dayh = 0;
                    } else {
                        $count->friday = 1;
                        $count->absent = null;
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



                $count->save();
            }
        }

        // dd($row[2]);



    }
    // public function onee(){
    // return 1 ;
    // }
}
