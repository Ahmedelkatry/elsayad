<?php

namespace App\Http\Controllers;

use App\Models\taminat;
use App\Models\employees;
use Illuminate\Http\Request;

class TaminatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $taminat = taminat::get();
        $emps = employees::get();
        return view("employees.taminat", compact("taminat", "emps"));
    }

    public function create_multi()
    {
        $employees = employees::get();
        return view("employees.multi_taminat", compact("employees"));
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
        $monys = (array) $request->mony;

        foreach ($employees_ids as $key => $emp_id) {
            if (!empty($emp_id) && isset($monys[$key])) {
                taminat::create([
                    'employees_id' => $emp_id,
                    'mony' => $monys[$key],
                ]);
            }
        }
        return back();
    }

    /**
     * Display the specified resource.
     */
    public function show(taminat $taminat)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(taminat $taminat)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $request->validate([
            "employees_id" => "required",
            "mony" => "required|min:0",
        ]);

        $data = $request->all();
        taminat::find($request->id)->update($data);
        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        taminat::find($request->id)->delete();
        return back();
    }
    public function bulk_delete(Request $request)
    {
        if ($request->ids) {
            \App\Models\taminat::whereIn('id', $request->ids)->delete();
            return back()->with('delete', 'تم حذف السجلات المختارة بنجاح');
        }
        return back()->with('error', 'يرجى اختيار سجلات للحذف');
    }
}
