<?php

namespace App\Http\Controllers;

use App\Models\employees;
use App\Models\please;
use Illuminate\Http\Request;

class PleaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
       $emps= employees::all();
        $plzs=please::all();
        return view("employees.please",compact("emps","plzs"));
    }

    public function create_multi()
    {
        $employees = employees::get();
        return view("employees.multi_please", compact("employees"));
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
        $dates = (array) $request->date;
        $in_outs = (array) $request->in_out;
        $dis_values = (array) $request->dis_value;
        $diss = (array) $request->dis;
        $minutess = (array) $request->minutes;
        $decs = (array) $request->dec;

        foreach ($employees_ids as $key => $emp_id) {
            if (!empty($emp_id) && !empty($dates[$key])) {
                please::create([
                    'employees_id' => $emp_id,
                    'date' => $dates[$key],
                    'in_out' => $in_outs[$key] ?? null,
                    'dis_value' => $dis_values[$key] ?? 0,
                    'dis' => $diss[$key] ?? 0,
                    'minutes' => $minutess[$key] ?? 0,
                    'dec' => $decs[$key] ?? null,
                ]);
            }
        }
        return back();
    }

    /**
     * Display the specified resource.
     */
    public function show(please $please)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(please $please)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,$id)
    {
        $data=$request->all();
        please::find($id)->update($data);
        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(request $request,$id)
    {
        
        please::find($id)->delete();
        return back();
    }
    public function bulk_delete(Request $request)
    {
        if ($request->ids) {
            \App\Models\please::whereIn('id', $request->ids)->delete();
            return back()->with('delete', 'تم حذف السجلات المختارة بنجاح');
        }
        return back()->with('error', 'يرجى اختيار سجلات للحذف');
    }
}
