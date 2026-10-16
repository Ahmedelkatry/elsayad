<?php

namespace App\Http\Controllers;

use App\Models\disemp;
use App\Models\employees;
use Illuminate\Http\Request;
use App\Http\Middleware\ahmed;
use App\Models\User;
use App\Notifications\dis;
use App\Notifications\salf_notiy;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class DisempController extends Controller
implements HasMiddleware
{





    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $employees = employees::get();
        $disemp = disemp::orderBy('id', 'desc')->get();
        return view("employees.disemp", compact("employees", "disemp"));
    }

    public function create_multi()
    {
        $employees = employees::get();
        return view("employees.multi_disemp", compact("employees"));
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
        $employees_ids = (array) $request->employees_id;
        $diss = (array) $request->dis;
        $dates = (array) $request->date;
        $decs = (array) $request->dec;

        foreach ($employees_ids as $key => $emp_id) {
            if (!empty($emp_id) && !empty($diss[$key]) && !empty($dates[$key])) {
                $emp = employees::findOrFail($emp_id);
                $dis = disemp::create([
                    'employees_id' => $emp_id,
                    'section_id' => $emp->section_id,
                    'dis' => $diss[$key],
                    'date' => $dates[$key],
                    'dec' => $decs[$key] ?? null,
                ]);

                $d = [
                    "masg" => "خصم " . $diss[$key],
                    "id" => $dis->id,
                    "date" => $dis->date,
                    "dec" => $dis->dec,
                    "type" => "dis",
                ];

                $user = User::where("num", $emp->num)->first();
                if ($user !== null) {
                    Notification::send($user, new salf_notiy($d));
                }
            }
        }
        return back();
    }

    /**
     * Display the specified resource.
     */
    public function show($id, $type)
    {
        dd($id);
        $dis = disemp::where("id", $id)->first();
        return view("employees.post_dis", compact("dis"));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(disemp $disemp)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {

        $request->validate([
            "employees_id" => "required",
            "dis" => "required",
            "date" => "required",
            "dec" => "required",
        ]);

        $data = $request->all();
        disemp::find($id)->update($data);
        return back();
    }
    public static function middleware()
    {
        return [
            ahmed::class
        ];
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        disemp::find($id)->delete();
        return back();
    }

    public function getemployees($id)
    {

        $emp = employees::where("id", $id)->first()->kabd;
        $emps = employees::where("id", $id)->first();

        $d = number_format($emp / $emps->sysemp);
        return json_encode($d);
    }

    public function getsec($id)
    {
        // return $id;
        if ($id == 1) {
            return (employees::where("num", "<", 1000)->latest()->first()->num+1 );
        }
         if ($id === 2) {
             return 44;
            // return (employees::where("num", ">", 1000)->latest()->first()->num+1 );
        }
        // else{
        //     return 1001 ;
        // }
    }
    public function bulk_delete(Request $request)
    {
        if ($request->ids) {
            \App\Models\disemp::whereIn('id', $request->ids)->delete();
            return back()->with('delete', 'تم حذف السجلات المختارة بنجاح');
        }
        return back()->with('error', 'يرجى اختيار سجلات للحذف');
    }
}
