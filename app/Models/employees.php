<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class employees extends Model
{
    protected $guarded = [];

    protected $casts = [
        'free_d' => 'array',
    ];

    public function section()
    {
        return $this->belongsTo(section::class);
    }

    public function shift()
    {
        return $this->belongsTo(shift::class);
    }

    public function countRelation()
    {
        return $this->hasMany(count::class);
    }

    public function nozkt()
    {
        return $this->hasMany(nozkt::class, 'emp_id');
    }

    public function salf()
    {
        return $this->hasMany(salf::class);
    }

    public function hafz()
    {
        return $this->hasMany(hafz::class);
    }

    public function vacation()
    {
        return $this->hasMany(vacation::class);
    }

    public function please()
    {
        return $this->hasMany(please::class);
    }

    public function taminat()
    {
        return $this->hasMany(taminat::class);
    }

    public function purchases()
    {
        return $this->hasMany(purchases::class);
    }

    public function disemp()
    {
        return $this->hasMany(disemp::class);
    }

    public function managedSections()
    {
        return $this->belongsToMany(section::class, 'manager_section', 'employee_id', 'section_id');
    }

    public function allowedLocations()
    {
        return $this->hasMany(EmployeeAllowedLocation::class, 'employee_id');
    }

    public function companyLocations()
    {
        return $this->belongsToMany(CompanyLocation::class, 'employee_company_location', 'employee_id', 'company_location_id')
                    ->withPivot('type');
    }

    public function getCountdayAttribute()
    {
        $days = 30;
        if ($this->choosesys == 3 && $this->sysemp > 0) {
            $days = $this->sysemp;
        } elseif ($this->choosesys == 2) {
            $holidays = (is_array($this->free_d) && $this->check == 1) ? count($this->free_d) * 4 : 0;
            $days = max(1, 30 - $holidays);
        }
        return $days > 0 ? round(($this->kabd ?? 0) / $days, 2) : 0;
    }

    public function getCounthAttribute()
    {
        $dayValue = $this->countday;
        $workh = $this->shift?->workh ?: 8;
        return $workh > 0 ? round($dayValue / $workh, 2) : 0;
    }
}
