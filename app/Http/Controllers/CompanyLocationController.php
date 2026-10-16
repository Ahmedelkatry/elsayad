<?php

namespace App\Http\Controllers;

use App\Models\CompanyLocation;
use App\Models\employees;
use Illuminate\Http\Request;

class CompanyLocationController extends Controller
{
    /**
     * جلب كل مواقع الشركة
     */
    public function index()
    {
        $locations = CompanyLocation::orderBy('name')->get();
        return response()->json($locations);
    }

    /**
     * إضافة موقع جديد للشركة
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'      => 'required|string|max:255',
            'latitude'  => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'radius'    => 'nullable|integer|min:10|max:5000',
        ]);

        $location = CompanyLocation::create([
            'name'      => $request->name,
            'latitude'  => $request->latitude,
            'longitude' => $request->longitude,
            'radius'    => $request->radius ?? 100,
        ]);

        return response()->json(['success' => true, 'location' => $location]);
    }

    /**
     * تعديل موقع الشركة
     */
    public function update(Request $request, $id)
    {
        $location = CompanyLocation::findOrFail($id);
        $request->validate([
            'name'      => 'required|string|max:255',
            'latitude'  => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'radius'    => 'nullable|integer|min:10|max:5000',
        ]);

        $location->update([
            'name'      => $request->name,
            'latitude'  => $request->latitude,
            'longitude' => $request->longitude,
            'radius'    => $request->radius ?? 100,
        ]);

        return response()->json(['success' => true, 'location' => $location]);
    }

    /**
     * حذف موقع الشركة
     */
    public function destroy($id)
    {
        CompanyLocation::findOrFail($id)->delete();
        return response()->json(['success' => true]);
    }

    /**
     * جلب مواقع موظف معين (المربوطة به)
     */
    public function getEmployeeLocations($employee_id)
    {
        $emp = employees::findOrFail($employee_id);
        $assigned = $emp->companyLocations()->get(['company_locations.id', 'employee_company_location.type']);
        // Return as [{ id: X, type: 'both'|'in'|'out' }, ...]
        return response()->json($assigned->map(fn($loc) => [
            'id'   => $loc->id,
            'type' => $loc->pivot->type ?? 'both',
        ]));
    }

    /**
     * حفظ/تحديث مواقع الموظف (تحديد اكثر من موقع)
     */
    public function syncEmployeeLocations(Request $request, $employee_id)
    {
        $request->validate([
            'locations'        => 'nullable|array',
            'locations.*.id'   => 'required|integer|exists:company_locations,id',
            'locations.*.type' => 'required|in:both,in,out',
        ]);

        $emp = employees::findOrFail($employee_id);

        // Build sync data: [ location_id => ['type' => 'both|in|out'] ]
        $syncData = [];
        foreach ($request->locations ?? [] as $item) {
            $syncData[$item['id']] = ['type' => $item['type']];
        }
        $emp->companyLocations()->sync($syncData);

        return response()->json(['success' => true]);
    }
}
