<?php

namespace App\Http\Controllers;

use com_exception;
use App\Models\salf;

use App\Models\User;
use App\Models\employees;
use Illuminate\Http\Request;
use App\Http\Middleware\ahmed;
use App\Imports\salfs;
use App\Notifications\salf_notiy;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Notification;
use App\Notifications\salf as NotificationsSalf;
use Illuminate\Routing\Controllers\HasMiddleware;

class SalfController extends Controller implements HasMiddleware
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $salfs = salf::with('managers')->get(); // Wait, salf doesn't have managers, it belongs to employees
        $salfs = salf::orderBy('id', 'desc')->get();
        $employees = employees::get();
        return view("employees.salf", compact("employees", "salfs"));
    }

    public function create_multi()
    {
        $employees = employees::get();
        return view("employees.multi_salf", compact("employees"));
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
        $salfs = (array) $request->salf;
        $dates = (array) $request->date;
        $decs = (array) $request->dec;

        foreach ($employees_ids as $key => $emp_id) {
            if (!empty($emp_id) && !empty($salfs[$key]) && !empty($dates[$key])) {
                $emp = employees::findOrFail($emp_id);
                $salf = salf::create([
                    'employees_id' => $emp_id,
                    'section_id' => $emp->section_id,
                    'salf' => $salfs[$key],
                    'date' => $dates[$key],
                    'dec' => $decs[$key] ?? null,
                ]);

                $d = [
                    "masg" => "تم قبول سلفه بقيمه " . $salfs[$key] . " جنيه",
                    "id" => $salf->id,
                    "date" => $salf->date,
                    "dec" => $salf->dec,
                    "type" => "salf",
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
    public function show(salf $salf)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(salf $salf)
    {
        //
    }
    public static function middleware()
    {
        return [
            ahmed::class
        ];
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            "employees_id" => "required",
            "salf" => "required",
            "date" => "required",

        ]);

        $data = $request->all();
        salf::find($id)->update($data);
        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        salf::find($id)->delete();
        return back();
    }

    public function salf_import(Request $request)
{

    Excel::import(new salfs, $request->file('file'));
    // Excel::import(new employeesImport, $request->file('file', \Maatwebsite\Excel\Excel::XLS));
    return back();
}
    public function bulk_delete(Request $request)
    {
        if ($request->ids) {
            salf::whereIn('id', $request->ids)->delete();
            return back()->with('delete', 'تم حذف السجلات المختارة بنجاح');
        }
        return back()->with('error', 'يرجى اختيار سجلات للحذف');
    }
}
