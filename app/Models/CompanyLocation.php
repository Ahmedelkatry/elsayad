<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyLocation extends Model
{
    protected $guarded = [];

    public function employees()
    {
        return $this->belongsToMany(employees::class, 'employee_company_location', 'company_location_id', 'employee_id')
                    ->withPivot('type');
    }
}
