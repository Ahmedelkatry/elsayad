<?php

namespace App\Http\Controllers;

use App\Models\change_shift;
use App\Models\employees;
use App\Models\please;
use App\Models\salf;
use App\Models\shift;
use App\Models\tlbplease;
use App\Models\User;
use App\Models\vacation;
use App\Notifications\salf_notiy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class show_talp extends Controller
{
    public function show_free()
    {
        $tlbs = tlbplease::all();

        return view('tlbs.show_free', compact('tlbs'));
    }

    public function ok_free(Request $request)
    {
        $free_ok = vacation::create([
            'date' => $request->date,
            'dec' => $request->dec,
            'disvac' => $request->disvac,
            'employees_id' => $request->employees_id,
        ]);

        tlbplease::find($request->id)->update([
            'status' => 0,
        ]);

        $free_ok = tlbplease::find($request->id);
        $d = [
            'masg' => "تم قبول الاجازه بتاريخ  $free_ok->date ",
            'id' => $free_ok->id,
            'date' => $free_ok->date,
            'dec' => $free_ok->dec,
            'type' => 'ok_free',
        ];
        $emp = User::where('num', employees::where('id', $request->employees_id)->first()->num)->first();
        Notification::send($emp, new salf_notiy($d));

        return back();
    }

    public function no_free(Request $request)
    {
        tlbplease::find($request->id)->update([
            'status' => 1,
        ]);

        $free = tlbplease::find($request->id);

        $d = [
            'masg' => " تم رفض الاجازه بتاريخ $free->date ",
            'id' => $free->id,
            'date' => $free->date,
            'dec' => $free->textfree,
            'type' => 'no_free',
        ];

        $emp = User::where('num', employees::where('id', $request->employees_id)->first()->num)->first();
        Notification::send($emp, new salf_notiy($d));

        return back();
    }

    /////////////////////////////////////////////////////////

    public function show_salf()
    {
        $tlbs = tlbplease::all();

        return view('tlbs.show_salf', compact('tlbs'));
    }

    public function ok_salf(Request $request)
    {
        $secid = employees::where('id', $request->employees_id)->first()->section_id;
        salf::create([
            'date' => $request->date,
            'salf' => $request->salf,
            'dec' => $request->dec,
            'employees_id' => $request->employees_id,
            'section_id' => $secid,
        ]);
        tlbplease::find($request->id)->update([
            'status' => 0,
        ]);

        $salf = tlbplease::find($request->id);
        $d = [
            'masg' => "تم قبول سلفه بقيمه $salf->salf جنيه",
            'id' => $salf->id,
            'date' => $salf->date,
            'dec' => $salf->dec,
            'type' => 'ok_salf',
        ];
        $emp = User::where('num', employees::where('id', $request->employees_id)->first()->num)->first();
        Notification::send($emp, new salf_notiy($d));

        return back();
    }

    public function no_salf(Request $request)
    {
        // dd($salf->salf);
        tlbplease::find($request->id)->update([
            'status' => 1,
        ]);
        $salf = tlbplease::find($request->id);
        $d = [
            'masg' => "تم رفض السلفه  $salf->salf ",
            'id' => $salf->id,
            'date' => $salf->datesalf,
            'dec' => $salf->textsalf,
            'type' => 'no_salf',
        ];
        $emp = User::where('num', employees::where('id', $request->employees_id)->first()->num)->first();
        Notification::send($emp, new salf_notiy($d));

        return back();
    }

    ////////////////////////////////////////////////

    public function show_plz()
    {
        $tlbs = tlbplease::all();

        return view('tlbs.show_plz', compact('tlbs'));
    }

    public function ok_plz(Request $request)
    {
        $secid = employees::where('id', $request->employees_id)->first()->section_id;
        please::create([
            'date' => $request->date,
            'in_out' => $request->in_out,
            'employees_id' => $request->employees_id,
            'minutes' => $request->min,
            'dis' => $request->dis,
            'dis_value' => $request->dis_value,
            'dec' => $request->dec,
        ]);
        tlbplease::find($request->id)->update([
            'status' => 0,
            'dis' => $request->dis,
            'dis_value' => $request->dis_value,

        ]);
        $plz_ok = tlbplease::find($request->id);

        $d = [
            'masg' => "تم قبول الاذن بتاريخ  $plz_ok->dateplz ",
            'id' => $plz_ok->id,
            'date' => $plz_ok->dateplz,
            'dec' => $plz_ok->textplz,
            'type' => 'ok_plz',
        ];
        $emp = User::where('num', employees::where('id', $request->employees_id)->first()->num)->first();
        Notification::send($emp, new salf_notiy($d));

        return back();
    }

    public function no_plz(Request $request)
    {
        tlbplease::find($request->id)->update([
            'status' => 1,
        ]);

        $plz = tlbplease::find($request->id);
        $d = [
            'masg' => " تم رفض الاذن بتاريخ $plz->dateplz ",
            'id' => $plz->id,
            'date' => $plz->dateplz,
            'dec' => $plz->textplz,
            'type' => 'no_plz',
        ];

        $emp = User::where('num', employees::where('id', $request->employees_id)->first()->num)->first();
        Notification::send($emp, new salf_notiy($d));

        return back();
    }

    //////////////////////////////////////////

    //////////////////////////////////

    public function show_shift()
    {
        $tlbs = tlbplease::all();
        $shifts = shift::all();

        return view('tlbs.show_shift', compact('tlbs', 'shifts'));
    }

    public function ok_shift(Request $request)
    {
        $secid = employees::where('id', $request->employees_id)->first()->section_id;
        change_shift::create([
            'date_start' => $request->date_start,
            'date_end' => $request->date_end,
            // "dec"=>$request->dec,
            'employees_id' => $request->employees_id,
            'shift_id' => $request->shift_id,
        ]);
        tlbplease::find($request->id)->update([
            'status' => 0,
        ]);

        $shift_name = Shift::where('id', $request->shift_id)->first()->name;
        $ch_shift = tlbplease::find($request->id);
        $d = [
            'masg' => "تم قبول تغير الشيفت الي $shift_name من تاريخ $ch_shift->dateshift الي تاريخ $ch_shift->dateshift_end",
            'id' => $ch_shift->id,
            'date' => $ch_shift->dateshift,
            'dec' => $ch_shift->textshift,
            'type' => 'ok_shift',
        ];
        $emp = User::where('num', employees::where('id', $request->employees_id)->first()->num)->first();
        Notification::send($emp, new salf_notiy($d));

        return back();
    }

    public function no_shift(Request $request)
    {

        tlbplease::find($request->id)->update([
            'status' => 1,
        ]);

        $shift = tlbplease::find($request->id);
        $name_shift = shift::where('id', $shift->shift_id)->first()->name;
        $d = [
            'masg' => " تم رفض  تغير الشيفت الى $name_shift ",
            'id' => $shift->id,
            'date' => $shift->dateshift,
            'dec' => $shift->textshift,
            'type' => 'no_shift',
        ];

        $emp = User::where('num', employees::where('id', $request->employees_id)->first()->num)->first();
        Notification::send($emp, new salf_notiy($d));

        return back();
    }

    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
