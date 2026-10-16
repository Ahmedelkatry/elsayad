<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\plzot;
use Illuminate\Http\Request;

class PlzotController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $start_date = $request->start ? Carbon::parse($request->start) : Carbon::now()->startOfMonth();
        $end_date = $request->end ? Carbon::parse($request->end) : Carbon::now()->endOfMonth();
        
        $query = plzot::whereBetween('date', [$start_date->format('Y-m-d'), $end_date->format('Y-m-d')]);

        if ($request->filled('numid')) {
            $query->where('numid', $request->numid);
        }

        $plzots = $query->get();
        $employees = \App\Models\employees::all();

        return view("plzot.plzot", compact("plzots", "employees"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $ids = $request->id;
        if (!$ids) {
            return back();
        }
        foreach ($ids as $id) {
            plzot::find($id)->update([
                "status" => 1,
            ]);
        }
        return back();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store($id)
    {

        // plzot::update
    }

    /**
     * Display the specified resource.
     */
    public function show($id)  // no
    {
        plzot::find($id)->update([
            "status" => 0,
        ]);
        return back();
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(plzot $plzot)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update($id) // ok
    {

        plzot::find($id)->update([
            "status" => 1,
        ]);
        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(plzot $plzot)
    {
        //
    }
}
