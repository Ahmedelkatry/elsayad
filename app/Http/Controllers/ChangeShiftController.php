<?php

namespace App\Http\Controllers;

use App\Models\change_shift;
use App\Models\employees;
use App\Models\shift;
use Illuminate\Http\Request;

class ChangeShiftController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $emps = employees::get();
        $shifts = shift::get();
        $chshifts = change_shift::get();
        return view("shifts.change_shift", compact("emps", "shifts", "chshifts"));
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
        $data = $request->all();
        change_shift::create($data);
        return back();
    }

    /**
     * Display the specified resource.
     */
    public function show(change_shift $change_shift)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(change_shift $change_shift)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, change_shift $change_shift)
    {
        $request->validate([
            "date_start" => "required",
            "date_end" => "required",
            "employees_id" => "required",
            "shift_id" => "required",
        ]);

        $data = $request->except($request->id);
        change_shift::find($request->id)->update($data);
        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
   public function destroy(Request $request)
    {
        change_shift::find($request->id)->delete();
        return back();
    }
}
