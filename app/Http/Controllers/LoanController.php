<?php

namespace App\Http\Controllers;

use App\Models\employees;
use App\Models\loan;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LoanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $loans = loan::get();
        $emps = employees::get();

        return view("loan.loan", compact("loans", "emps"));
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
        $d_start = Carbon::parse($request->date_start);
        $d_end = Carbon::parse($request->date_end)->addMonth();

        $month = $d_start->diffInMonths($d_end);
        $mony_now = $request->mony / $month;

        loan::create([
            "employees_id" => $request->employees_id,
            "mony" => $request->mony,
            "mony_now" => $mony_now,
            "mony_after" => $request->mony_after,
            "date_start" => $request->date_start,
            "date_end" => $request->date_end,
            "month" => $month,
            "month_now" => $month,
        ]);
        return back();
    }

    /**
     * Display the specified resource.
     */
    public function show(loan $loan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(loan $loan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, loan $loan)
    {
        $d_start = Carbon::parse($request->date_start);
        $d_end = Carbon::parse($request->date_end)->addMonth();

        $month = $d_start->diffInMonths($d_end);
        $mony_now = $request->mony / $month;

        loan::find($loan->id)->update([
            "employees_id" => $request->employees_id,
            "mony" => $request->mony,
            "mony_now" => $mony_now,
            "mony_after" => $request->mony_after,
            "date_start" => $request->date_start,
            "date_end" => $request->date_end,
            "month" => $month,
            "month_now" => $month,
        ]);
        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(loan $loan)
    {
        loan::find($loan->id)->delete();
        return back();
    }

    public function bulk_delete(Request $request)
    {
        if ($request->ids) {
            loan::whereIn('id', $request->ids)->delete();
            return back()->with('delete', 'تم حذف البيانات المحددة بنجاح');
        }
        return back();
    }
}
