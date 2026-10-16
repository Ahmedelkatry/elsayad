<?php

namespace App\Http\Controllers;

use App\Models\employees;
use App\Models\purchases;
use Illuminate\Http\Request;

class PurchasesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $purchases = purchases::get();
        $emps = employees::get();
        return view("employees.purchases", compact("purchases", "emps"));
    }

    public function create_multi()
    {
        $employees = employees::get();
        return view("employees.multi_purchases", compact("employees"));
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
        $dates = (array) $request->date;

        foreach ($employees_ids as $key => $emp_id) {
            if (!empty($emp_id) && isset($monys[$key])) {
                purchases::create([
                    'employees_id' => $emp_id,
                    'mony' => $monys[$key],
                    'date' => $dates[$key] ?? date('Y-m-d'),
                ]);
            }
        }
        return back();
    }

    /**
     * Display the specified resource.
     */
    public function show(purchases $purchases)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(purchases $purchases)
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
            "date" => "required",
        ]);

        $data = $request->all();
        purchases::find($request->id)->update($data);
        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        purchases::find($request->id)->delete();
        return back();
    }
    public function bulk_delete(Request $request)
    {
        if ($request->ids) {
            \App\Models\purchases::whereIn('id', $request->ids)->delete();
            return back()->with('delete', 'تم حذف السجلات المختارة بنجاح');
        }
        return back()->with('error', 'يرجى اختيار سجلات للحذف');
    }
}
