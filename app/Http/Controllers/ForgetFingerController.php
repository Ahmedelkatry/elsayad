<?php

namespace App\Http\Controllers;

use App\Models\employees;
use App\Models\forget_finger;
use Illuminate\Http\Request;

class ForgetFingerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $forget= forget_finger::all();
        $emps= employees::all();
        return view("zktcount.forget_finger",compact("forget","emps"));
    }

    public function create_multi()
    {
        $employees = employees::get();
        return view("zktcount.multi_forget_finger", compact("employees"));
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
        $ins = (array) $request->in;
        $outs = (array) $request->out;

        foreach ($employees_ids as $key => $emp_id) {
            if (!empty($emp_id) && !empty($dates[$key])) {
                forget_finger::create([
                    'employees_id' => $emp_id,
                    'date' => $dates[$key],
                    'in' => $ins[$key] ?? null,
                    'out' => $outs[$key] ?? null,
                ]);
            }
        }
        return back();
    }

    /**
     * Display the specified resource.
     */
    public function show(forget_finger $forget_finger)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(forget_finger $forget_finger)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $data=$request->all();
        forget_finger::find($id)->update($data);
        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        forget_finger::find($id)->delete();
 return back();
    }
    public function bulk_delete(Request $request)
    {
        if ($request->ids) {
            \App\Models\forget_finger::whereIn('id', $request->ids)->delete();
            return back()->with('delete', 'تم حذف السجلات المختارة بنجاح');
        }
        return back()->with('error', 'يرجى اختيار سجلات للحذف');
    }
}
