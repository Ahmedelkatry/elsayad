<?php

namespace App\Http\Controllers;

use App\Models\section;
use Illuminate\Http\Request;
use App\Models\head_sections;
use App\Models\shift;

class HeadSectionsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sections = section::get();
        $shifts = shift::get();
        $ch_sh_secs = head_sections::get();

        return view("shifts.change_shift_section", compact("sections", "shifts", "ch_sh_secs"));
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
            "date_start" => "required",
            "date_end" => "required",
            "section_id" => "required",
            "shift_id" => "required",
        ]);
        $data = $request->all();
        head_sections::create($data);

        return back();
    }

    /**
     * Display the specified resource.
     */
    public function show(head_sections $head_sections)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(head_sections $head_sections)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $request->validate([
            "date_start" => "required",
            "date_end" => "required",
            "section_id" => "required",
            "shift_id" => "required",
        ]);

        $data = $request->except($request->id);
        head_sections::find($request->id)->update($data);
        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        head_sections::find($request->id)->delete();
        return back();
    }
}
