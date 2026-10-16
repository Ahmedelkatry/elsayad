<?php

namespace App\Http\Controllers;

use App\Models\eid;
use Illuminate\Http\Request;

class EidController extends Controller
{
    public function index()
    {

        $eids = eid::all();
        return view("eid.eid", compact("eids"));
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
            "name" => "required",
            "date" => "required"
        ]);


        $data = $request->all();
        eid::create($data);
        return back();
    }

    /**
     * Display the specified resource.
     */
    public function show()
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit()
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            "name" => "required",
            "date" => "required"
        ]);


        $data = $request->all();
        eid::find($id)->update($data);
        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        eid::find($id)->delete();
        return back();
    }

    public function bulk_delete(Request $request)
    {
        if ($request->ids) {
            eid::whereIn('id', $request->ids)->delete();
            return back()->with('delete', 'تم حذف البيانات المحددة بنجاح');
        }
        return back();
    }
}
