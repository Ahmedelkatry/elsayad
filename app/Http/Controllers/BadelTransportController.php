<?php

namespace App\Http\Controllers;

use App\Models\employees;
use Illuminate\Http\Request;
use App\Models\badel_transport;

class BadelTransportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $badel_ts = badel_transport::get();
        $emps = employees::get();
        return view("transport.badel_transport", compact("badel_ts", "emps"));
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
        $request->validate([
            "employees_id" => "required",
            "mony" => "required|min:0",
        ]);

        $data = $request->all();
        badel_transport::create($data);
        return back();
    }

    /**
     * Display the specified resource.
     */
    public function show(badel_transport $badel_transport)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(badel_transport $badel_transport)
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
        badel_transport::find($request->id)->update($data);
        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        badel_transport::find($request->id)->delete();
        return back();
    }

    public function bulk_delete(Request $request)
    {
        if ($request->ids) {
            badel_transport::whereIn('id', $request->ids)->delete();
            return back()->with('delete', 'تم حذف البيانات المحددة بنجاح');
        }
        return back();
    }
}
