<?php

namespace App\Http\Controllers;

use App\Models\hafzeid;
use Illuminate\Http\Request;

class HafzeidController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $hafzeid = hafzeid::all();
        return view("eid.hafzeid", compact("hafzeid"));
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
            "hafzeid" => "required",
            "date" => "required"
        ]);


        $data = $request->all();
        hafzeid::create($data);
        return back();
    }

    /**
     * Display the specified resource.
     */
    public function show(hafzeid $hafzeid)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(hafzeid $hafzeid)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            "hafzeid" => "required",
            "date" => "required"
        ]);


        $data = $request->all();
        hafzeid::find($id)->update($data);
        return back();
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        hafzeid::find($id)->delete();
        return back();
    }

    public function bulk_delete(Request $request)
    {
        if ($request->ids) {
            hafzeid::whereIn('id', $request->ids)->delete();
            return back()->with('delete', 'تم حذف البيانات المحددة بنجاح');
        }
        return back();
    }
}
