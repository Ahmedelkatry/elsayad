<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\shift;
use App\Models\employees;
use App\Models\tlbplease;
use Illuminate\Http\Request;
use App\Notifications\salf_notiy;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class TlbpleaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $tlbs = tlbplease::where("num", Auth::user()->num)->get();

        return view("tlbs.tlbfree", compact("tlbs"));
    }




    public function indexsalf()
    {
        $tlbs = tlbplease::where("num", Auth::user()->num)->get();
        return view("tlbs.tlbsalf", compact("tlbs"));
    }


    public function indexshift()
    {
        $tlbs = tlbplease::where("num", Auth::user()->num)->get();
        $shifts = shift::all();

        return view("tlbs.tlbshift", compact("tlbs", "shifts"));
    }


    public function indexplz()
    {
        $tlbs = tlbplease::where("num", Auth::user()->num)->get();
        return view("tlbs.tlbplz", compact("tlbs"));
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


        $employees_id = employees::where("num", $request->num)->first()->id;
        $shift_id = employees::where("num", $request->num)->first()->shift_id;
        $noti= tlbplease::create([
            "date" => $request->date,
            "num" => $request->num,
            "employees_id" => $employees_id,
            "textfree" => $request->textfree,
            "name" => $request->name,
            "shift_id" => $shift_id,
            "status" => 3,
        ]);

        $d=[
            "masg"=> "طلب اجازه يوم $noti->date" ,
            "id"=> $noti->id,
            "date"=> $noti->created_at,
            "dec"=> $noti->textfree,
            "type"=> "tlpfree",
        ];

        $owners = User::whereHas("roles", function($q){ $q->where("name", "owner"); })->get();
        $deptManagers = User::whereHas("roles", function($q){ $q->where("name", "رئيس قسم"); })->get()->filter(function($user) use ($employees_id) {
            $emp = employees::find($employees_id);
            return $emp && in_array($emp->section_id, $user->managedSectionIds());
        });
        $recipients = $owners->merge($deptManagers);
        Notification::send($recipients, new salf_notiy($d));

        return back();
    }

    public function storesalf(Request $request)
    {
        $employees_id = employees::where("num", $request->num)->first()->id;
        $shift_id = employees::where("num", $request->num)->first()->shift_id;
        $noti= tlbplease::create([
            "datesalf" => $request->datesalf,
            "num" => $request->num,
            "employees_id" => $employees_id,
            "textsalf" => $request->textsalf,
            "name" => $request->name,
            "salf" => $request->salf,
            "shift_id" => $shift_id,
            "status" => 3,
        ]);

        $d=[
            "masg"=> "طلب سلفه بقيمه $noti->salf" ,
            "id"=> $noti->id,
            "date"=> $noti->created_at,
            "dec"=> $noti->textsalf,
            "type"=> "tlpsalf",
        ];

        $owners = User::whereHas("roles", function($q){ $q->where("name", "owner"); })->get();
        $deptManagers = User::whereHas("roles", function($q){ $q->where("name", "رئيس قسم"); })->get()->filter(function($user) use ($employees_id) {
            $emp = employees::find($employees_id);
            return $emp && in_array($emp->section_id, $user->managedSectionIds());
        });
        $recipients = $owners->merge($deptManagers);
        Notification::send($recipients, new salf_notiy($d));

        return back();
    }

    public function storeshift(Request $request)
    {
        $employees_id = employees::where("num", $request->num)->first()->id;
        $noti=  tlbplease::create([
            "dateshift" => $request->dateshift,
            "num" => $request->num,
            "employees_id" => $employees_id,
            "textshift" => $request->textshift,
            "name" => $request->name,
            "shift_id" => $request->shift_id,
            "dateshift_end" => $request->dateshift_end,
            "status" => 3,
        ]);

        $shift_name = Shift::where("id",$request->shift_id)->first()->name;
        $d=[
            "masg"=> "طلب تبديل شيفت الي $shift_name من تاريخ $request->dateshift الي تاريخ $request->dateshift_end" ,
            "id"=> $noti->id,
            "date"=> $noti->created_at,
            "dec"=> $noti->textshift,
            "type"=> "tlpchange_shift",
        ];

        $owners = User::whereHas("roles", function($q){ $q->where("name", "owner"); })->get();
        $deptManagers = User::whereHas("roles", function($q){ $q->where("name", "رئيس قسم"); })->get()->filter(function($user) use ($employees_id) {
            $emp = employees::find($employees_id);
            return $emp && in_array($emp->section_id, $user->managedSectionIds());
        });
        $recipients = $owners->merge($deptManagers);
        Notification::send($recipients, new salf_notiy($d));

        return back();
    }


    public function storeplz(Request $request)
    {
        $employees_id = employees::where("num", $request->num)->first()->id;
        $shift_id = employees::where("num", $request->num)->first()->shift_id;
        $noti=  tlbplease::create([
            "dateplz" => $request->dateplz,
            "num" => $request->num,
            "employees_id" => $employees_id,
            "textplz" => $request->textplz,
            "name" => $request->name,
            "in_out" => $request->in_out,
            "min" => $request->min,
            "shift_id" => $shift_id,
            "status" => 3,
        ]);
        
       $d=[
            "masg"=> "طلب اذن  يوم $noti->dateplz" ,
            "id"=> $noti->id,
            "date"=> $noti->created_at,
            "dec"=> $noti->textplz,
            "type"=> "tlpplz",
        ];

        $owners = User::whereHas("roles", function($q){ $q->where("name", "owner"); })->get();
        $deptManagers = User::whereHas("roles", function($q){ $q->where("name", "رئيس قسم"); })->get()->filter(function($user) use ($employees_id) {
            $emp = employees::find($employees_id);
            return $emp && in_array($emp->section_id, $user->managedSectionIds());
        });
        $recipients = $owners->merge($deptManagers);
        Notification::send($recipients, new salf_notiy($d));

        return back();
    }

    /**
     * Display the specified resource.
     */
    public function show(tlbplease $tlbplease)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(tlbplease $tlbplease)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        tlbplease::find($request->id)->update([
            "date" => $request->date,
            "textfree" => $request->textfree,

        ]);

        return back();
    }

    public function updatesalf(Request $request)
    {
        tlbplease::find($request->id)->update([
            "datesalf" => $request->datesalf,
            "textsalf" => $request->textsalf,
            "salf" => $request->salf,
        ]);
        return back();
    }

    public function updateshift(Request $request)
    {
        tlbplease::find($request->id)->update([
            "dateshift" => $request->dateshift,
            "textshift" => $request->textshift,
            "shift_id" => $request->shift_id,
            "dateshift_end" => $request->dateshift_end,
        ]);
        return back();
    }


    public function updateplz(Request $request)
    {
        tlbplease::find($request->id)->update([

            "dateplz" => $request->dateplz,
            "textplz" => $request->textplz,
            "in_out" => $request->in_out,
            "min" => $request->min,
        ]);
        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        tlbplease::find($request->id)->delete();
        return back();
    }

    public function destroysalf(Request $request)
    {
        tlbplease::find($request->id)->delete();
        return back();
    }

    public function destroyshift(Request $request)
    {
        tlbplease::find($request->id)->delete();
        return back();
    }

    public function destroyplz(Request $request)
    {
        tlbplease::find($request->id)->delete();
        return back();
    }
}
