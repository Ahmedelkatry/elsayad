<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalEmployees = \App\Models\employees::query()->count();
        $totalSections = \App\Models\section::query()->count();
        $totalShifts = \App\Models\shift::query()->count();
        
        return view('index', compact('totalEmployees', 'totalSections', 'totalShifts'));
    }

    public function setVersion($version)
    {
        if (in_array($version, ['new', 'old'])) {
            session(['dashboard_version' => $version]);
        }

        return redirect()->route('dashboard');
    }

    public function requestPlan(Request $request)
    {
        return back()->with('info', 'هذه الميزة غير متاحة في الوضع الحالي.');
    }
}
