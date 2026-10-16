<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EmployeeLocationController extends Controller
{
    public function index()
    {
        // Get today's date
        $today = now()->format('Y-m-d');

        // Query the 'counts' table where locations exist for today
        $locations = \App\Models\count::where('date', $today)
            ->where(function($query) {
                $query->whereNotNull('latitude_in')->whereNotNull('longitude_in')
                      ->orWhereNotNull('latitude_out')->whereNotNull('longitude_out');
            })
            ->get()
            ->map(function($record) {
                return [
                    'employee_id' => $record->employees_id,
                    'employee_name' => $record->name,
                    'lat_in' => $record->latitude_in,
                    'lon_in' => $record->longitude_in,
                    'lat_out' => $record->latitude_out,
                    'lon_out' => $record->longitude_out,
                    'in_time' => $record->in,
                    'out_time' => $record->out,
                    'date' => $record->date
                ];
            });

        return view('employees.check_attendance_location', compact('locations'));
    }

    public function history(Request $request)
    {
        $employeeId = $request->query('employee_id');
        $date = $request->query('date', now()->format('Y-m-d'));

        $employee = \App\Models\employees::findOrFail($employeeId);

        $locations = \App\Models\EmployeeLocation::where('employees_id', $employeeId)
            ->whereDate('recorded_at', $date)
            ->orderBy('recorded_at', 'asc')
            ->get();

        return view('employees.location_history', compact('employee', 'locations', 'date'));
    }

    public function report(Request $request)
    {
        $employeeId = $request->query('employee_id');
        $fromDate = $request->query('from_date');
        $toDate = $request->query('to_date');

        $employees = \App\Models\employees::orderBy('name')->get();

        $query = \App\Models\EmployeeLocation::with('employees');

        if ($employeeId) {
            $query->where('employees_id', $employeeId);
        }

        if ($fromDate) {
            $query->whereDate('recorded_at', '>=', $fromDate);
        }

        if ($toDate) {
            $query->whereDate('recorded_at', '<=', $toDate);
        }

        $locations = $query->orderBy('recorded_at', 'desc')->paginate(30);

        return view('employees.locations_report', compact('employees', 'locations', 'employeeId', 'fromDate', 'toDate'));
    }
}
