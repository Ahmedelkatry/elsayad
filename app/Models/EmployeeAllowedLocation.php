<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeAllowedLocation extends Model
{
    protected $guarded = [];

    /**
     * حساب المسافة بين إحداثيين بالمتر (Haversine formula)
     */
    public static function haversineDistance(float $lat1, float $lon1, float $lat2, float $lon2): float
    {
        $earthRadius = 6371000; // بالمتر

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2)
            + cos(deg2rad($lat1)) * cos(deg2rad($lat2))
            * sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }

    /**
     * التحقق مما إذا كانت الإحداثيات داخل النطاق المسموح
     */
    public function isWithinRadius(float $latitude, float $longitude): bool
    {
        $distance = self::haversineDistance(
            $this->latitude,
            $this->longitude,
            $latitude,
            $longitude
        );

        return $distance <= $this->radius;
    }

    public function employee()
    {
        return $this->belongsTo(employees::class, 'employee_id');
    }
}
