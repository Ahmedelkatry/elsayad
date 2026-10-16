<?php

namespace App\Http\Controllers;

use App\Models\shift;
use App\Models\SupShift;
use Illuminate\Http\Request;
use App\Http\Middleware\ahmed;
use Illuminate\Routing\Controllers\HasMiddleware;

class SupShiftController extends Controller implements HasMiddleware
{
    public static function middleware()
    {
        return [
            ahmed::class
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sup_shifts = SupShift::with(['baseShift', 'overrideShift'])->get();
        return view("sup_shifts.index", compact("sup_shifts"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $shifts = shift::all();
        return view("sup_shifts.create", compact("shifts"));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'shift_id' => 'required|exists:shifts,id',
            'override_shift_id' => 'required|exists:shifts,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        SupShift::create($request->all());

        return redirect()->route('sup_shifts.index')->with('Add', 'تم إضافة التعديل بنجاح');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $sup_shift = SupShift::findOrFail($id);
        $shifts = shift::all();
        return view("sup_shifts.edit", compact("sup_shift", "shifts"));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'shift_id' => 'required|exists:shifts,id',
            'override_shift_id' => 'required|exists:shifts,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        SupShift::findOrFail($id)->update($request->all());

        return redirect()->route('sup_shifts.index')->with('edit', 'تم تحديث التعديل بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        SupShift::findOrFail($id)->delete();
        return back()->with('delete', 'تم حذف التعديل بنجاح');
    }
}
