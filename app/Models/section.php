<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class section extends Model
{
    use \App\Traits\RestrictsBySection;
    protected  $guarded = [];

    public function employees()
    {
        return $this->hasMany(employees::class);
    }

    public function count()
    {
        return $this->hasMany(count::class);
    }

    public function disemp()
    {
        return $this->hasMany(disemp::class);
    }

    public function salf()
    {
        return $this->hasMany(salf::class);
    }

    public function headsection()
    {
        return $this->hasMany(head_sections::class);
    }

    public function managers()
    {
        return $this->belongsToMany(employees::class, 'manager_section', 'section_id', 'employee_id');
    }
}
