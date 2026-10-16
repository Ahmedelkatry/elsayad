<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeLocation extends Model
{
    protected  $guarded = [];

    protected $casts = [
        'recorded_at' => 'datetime',
    ];

     public function employees()
    {
        return $this->belongsTo(employees::class);
    }
}
