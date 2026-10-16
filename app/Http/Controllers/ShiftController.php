<?php

namespace App\Http\Controllers;

use App\Models\shift;
use Illuminate\Http\Request;
use App\Http\Middleware\ahmed;
use Illuminate\Routing\Controllers\HasMiddleware;

class ShiftController extends Controller implements HasMiddleware
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $shifts = shift::get();
        return view("shifts.shifts", compact("shifts"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("shifts.add_shift");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        // dd($request->shift_h);

        if ($request->shift_time == 1) {
            $request->validate([
                'from' => "required",
                'to' => "required",
                'start_ot' => "required",
            ]);
        }

        $request->validate([
            'name' => "required",
            // 'from' => "required",
            // 'to' => "required",
            'late' => "required",
            // 'bdry' => "required",
            'workh' => "required",
            'dism' => "required",
            'absent' => "required",
            // 'start_ot' => "required",
            'ot' => "required"

        ]);

        $data = $request->all();
        shift::create($data);
        return redirect("/shifts");
    }
    public static function middleware()
    {
        return [
            ahmed::class
        ];
    }

    /**
     * Display the specified resource.
     */
    public function show()
    {
        $shifts = shift::get();
        return view("shifts.break", compact("shifts"));
    }


    public function update_break(Request $request, $id)
    {
        $request->validate([
            'break_in' => "required",
            'break_out' => "required",
            'b_out_dis' => "required",
            'b_in_dis' => "required",

        ]);
        $shift = (shift::find($id)->update([
            "break_in" => $request->break_in,
            "break_out" => $request->break_out,
            "b_out_dis" => $request->b_out_dis,
            "b_in_dis" => $request->b_in_dis,
        ]));
        return back();
        // return view("shifts.break", compact("shifts"));
    }

    public function del_break(Request $request, $id)
    {

        $request->validate([
            'break_in' => "required",
            'break_out' => "required",
            'b_out_dis' => "required",
            'b_in_dis' => "required",

        ]);

        //  dd(shift::find($id)->first());
        $shift = (shift::find($id)->update([
            "break_in" => null,
            "break_out" => null,
            "b_out_dis" => null,
            "b_in_dis" => null,
        ]));
        return back();
        // return view("shifts.break", compact("shifts"));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(shift $shift)
    {

        return view("shifts.edit_shift", compact("shift"));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        if ($request->shift_time == 1) //  شيفت كامل
        {
            // dd($id);
            $request->validate([
                'name' => "required",
                'from' => "required",
                'to' => "required",
                'late' => "required",
                'late1' => "required",
                'late2' => "required",
                'late3' => "required",
                'dism' => "required",
                'dism1' => "required",
                'dism2' => "required",
                'dism3' => "required",
                'bdry' => "required",
                'workh' => "required",
                'absent' => "required",
                'ot' => "required",
                'start_ot' => "required",
                'disout' => "required",

            ]);
            shift::find($id)->update([
                "shift_time" => $request->shift_time,
                'name' => $request->name,
                'from' => $request->from,
                'to' => $request->to,
                'late' => $request->late,
                'late1' => $request->late1,
                'late2' => $request->late2,
                'late3' => $request->late3,
                'dism' => $request->dism,
                'dism1' => $request->dism1,
                'dism2' => $request->dism2,
                'dism3' => $request->dism3,
                'bdry' => $request->bdry,
                'workh' => $request->workh,
                'absent' => $request->absent,
                'ot' => $request->ot,
                'start_ot' => $request->start_ot,
                'disout' => $request->disout,
            ]);
            // dd(11);
            return back();
        } else {
            $request->validate([
                'name' => "required",
                'late' => "required",
                'late1' => "required",
                'late2' => "required",
                'late3' => "required",
                'dism' => "required",
                'dism1' => "required",
                'dism2' => "required",
                'dism3' => "required",
                'workh' => "required",
                'absent' => "required",
                'ot' => "required",

            ]);

            shift::find($id)->update([
                'name' => $request->name,
                // 'from' => $request->from,
                // 'to' => $request->to,
                'late' => $request->late,
                'late1' => $request->late1,
                'late2' => $request->late2,
                'late3' => $request->late3,
                'dism' => $request->dism,
                'dism1' => $request->dism1,
                'dism2' => $request->dism2,
                'dism3' => $request->dism3,
                // 'bdry' => $request->bdry,
                'workh' => $request->workh,
                'absent' => $request->absent,
                'ot' => $request->ot,
                // 'start_ot' => $request->start_ot,
                // 'disout' => $request->disout,
                "break_in" => $request->break_in ?? null,
                "break_out" => $request->break_out ?? null,
                "b_out_dis" => $request->b_out_dis ?? null,
                "b_in_dis" => $request->b_in_dis ?? null,
            ]);
            return back();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $id)
    {
        shift::find($id)->delete();
        return back();
    }

    public function bulk_delete(Request $request)
    {
        if ($request->ids) {
            shift::whereIn('id', $request->ids)->delete();
            return back()->with('delete', 'تم حذف البيانات المحددة بنجاح');
        }
        return back();
    }
}
