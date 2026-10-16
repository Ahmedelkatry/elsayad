<?php

namespace App\Http\Controllers;

use App\Models\employees;
use App\Models\vacation;
use Illuminate\Http\Request;

class VacationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $emps=employees::all();
        $vacations=vacation::all();
        return view("employees.vacation",compact("emps","vacations"));
    }

    public function create_multi()
    {
        $employees = employees::get();
        return view("employees.multi_vacation", compact("employees"));
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
        $decs = (array) $request->dec;
        $disvacs = (array) $request->disvac;

        foreach ($employees_ids as $key => $emp_id) {
            if (!empty($emp_id) && !empty($dates[$key])) {
                vacation::create([
                    'employees_id' => $emp_id,
                    'date' => $dates[$key],
                    'dec' => $decs[$key] ?? null,
                    'disvac' => $disvacs[$key] ?? 1,
                ]);
            }
        }
        return back();
    }

    /**
     * Display the specified resource.
     */
    public function show(vacation $vacation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(vacation $vacation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();
        vacation::find($id)->update($data);
        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        vacation::find($id)->delete();
        return back();
    }
    public function bulk_delete(Request $request)
    {
        if ($request->ids) {
            \App\Models\vacation::whereIn('id', $request->ids)->delete();
            return back()->with('delete', 'تم حذف السجلات المختارة بنجاح');
        }
        return back()->with('error', 'يرجى اختيار سجلات للحذف');
    }
}
