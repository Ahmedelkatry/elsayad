<?php

namespace App\Http\Controllers;

use App\Models\hafz;
use App\Models\salf;
use App\Models\User;
use App\Models\employees;
use Illuminate\Http\Request;
use App\Notifications\salf_notiy;
use Illuminate\Support\Facades\Notification;

class HafzController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $hafz = hafz::orderBy('id', 'desc')->get();
        $employees = employees::all();
        return view("employees.hafz", compact("hafz", "employees"));
    }

    public function create_multi()
    {
        $employees = employees::get();
        return view("employees.multi_hafz", compact("employees"));
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
        $hafzs = (array) $request->hafz;
        $dates = (array) $request->date;
        $decs = (array) $request->dec;

        foreach ($employees_ids as $key => $emp_id) {
            if (!empty($emp_id) && !empty($hafzs[$key]) && !empty($dates[$key])) {
                $emp = employees::findOrFail($emp_id);
                $hafz = hafz::create([
                    'employees_id' => $emp_id,
                    'section_id' => $emp->section_id,
                    'hafz' => $hafzs[$key],
                    'date' => $dates[$key],
                    'dec' => $decs[$key] ?? null,
                ]);

                $d = [
                    "masg" => "حافز " . $hafzs[$key],
                    "id" => $hafz->id,
                    "date" => $hafz->date,
                    "dec" => $hafz->dec,
                    "type" => "hafz",
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
    public function show(hafz $hafz)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(hafz $hafz)
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
            "hafz" => "required",
            "date" => "required",
            "dec" => "required",
        ]);
        $data = $request->all();
        hafz::find($id)->update($data);
        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        hafz::find($id)->delete();
        return back();
    }
    public function bulk_delete(Request $request)
    {
        if ($request->ids) {
            \App\Models\hafz::whereIn('id', $request->ids)->delete();
            return back()->with('delete', 'تم حذف السجلات المختارة بنجاح');
        }
        return back()->with('error', 'يرجى اختيار سجلات للحذف');
    }
}
