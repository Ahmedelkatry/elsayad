<?php

namespace App\Http\Controllers;

use App\Models\EmployeeAllowedLocation;
use App\Models\employees;
use Illuminate\Http\Request;

class EmployeeAllowedLocationController extends Controller
{
    /**
     * جلب مواقع موظف معين
     */
    public function getByEmployee($employee_id)
    {
        $locations = EmployeeAllowedLocation::where('employee_id', $employee_id)->get();
        return response()->json($locations);
    }

    /**
     * حفظ موقع جديد للموظف
     */
    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|integer',
            'name'        => 'required|string|max:255',
            'latitude'    => 'required|numeric|between:-90,90',
            'longitude'   => 'required|numeric|between:-180,180',
            'radius'      => 'nullable|integer|min:10|max:5000',
        ]);

        $location = EmployeeAllowedLocation::create([
            'employee_id' => $request->employee_id,
            'name'        => $request->name,
            'latitude'    => $request->latitude,
            'longitude'   => $request->longitude,
            'radius'      => $request->radius ?? 100,
        ]);

        return response()->json([
            'success' => true,
            'location' => $location,
        ]);
    }

    /**
     * حذف موقع
     */
    public function destroy($id)
    {
        $location = EmployeeAllowedLocation::findOrFail($id);
        $location->delete();

        return response()->json(['success' => true]);
    }

    /**
     * API: التحقق من موقع الموظف عند البصمة
     */
    public function checkLocation(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|integer',
            'latitude'    => 'required|numeric',
            'longitude'   => 'required|numeric',
        ]);

        $emp = employees::findOrFail($request->employee_id);

        // أولاً: تحقق من المواقع الجديدة (company_locations عبر pivot)
        $companyLocations = $emp->companyLocations()->get();

        if ($companyLocations->isNotEmpty()) {
            foreach ($companyLocations as $loc) {
                $distance = EmployeeAllowedLocation::haversineDistance(
                    (float) $loc->latitude,
                    (float) $loc->longitude,
                    (float) $request->latitude,
                    (float) $request->longitude
                );
                if ($distance <= $loc->radius) {
                    return response()->json([
                        'allowed'       => true,
                        'matched_place' => $loc->name,
                        'message'       => 'موقعك مسموح به: ' . $loc->name,
                    ]);
                }
            }
            return response()->json([
                'allowed' => false,
                'message' => 'موقعك خارج النطاق المسموح. تأكد أنك في أحد الفروع المعتمدة.',
            ], 403);
        }

        // ثانياً: تحقق من المواقع القديمة (employee_allowed_locations) للتوافق
        $oldLocations = EmployeeAllowedLocation::where('employee_id', $request->employee_id)->get();

        if ($oldLocations->isEmpty()) {
            return response()->json([
                'allowed' => true,
                'message' => 'لا توجد قيود مواقع لهذا الموظف',
            ]);
        }

        foreach ($oldLocations as $loc) {
            if ($loc->isWithinRadius($request->latitude, $request->longitude)) {
                return response()->json([
                    'allowed'       => true,
                    'matched_place' => $loc->name,
                    'message'       => 'موقعك مسموح به: ' . $loc->name,
                ]);
            }
        }

        return response()->json([
            'allowed' => false,
            'message' => 'موقعك غير مسموح به للبصمة. تأكد أنك في أحد المواقع المعتمدة.',
        ], 403);
    }
}
