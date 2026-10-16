<?php

namespace App\Http\Controllers;

use App\Models\dis_transport;
use App\Models\employees;
use Illuminate\Http\Request;

class DisTransportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $dis_ts = dis_transport::get();
        $emps = employees::get();
        return view("transport.dis_transport", compact("dis_ts", "emps"));
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
        dis_transport::create($data);
        return back();
    }

    /**
     * Display the specified resource.
     */
    public function show(dis_transport $dis_transport)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(dis_transport $dis_transport)
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
        dis_transport::find($request->id)->update($data);
        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        dis_transport::find($request->id)->delete();
        return back();
    }

    public function bulk_delete(Request $request)
    {
        if ($request->ids) {
            dis_transport::whereIn('id', $request->ids)->delete();
            return back()->with('delete', 'تم حذف البيانات المحددة بنجاح');
        }
        return back();
    }
}
