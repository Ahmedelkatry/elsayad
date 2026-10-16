<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class shift extends Model
{
    protected $table = 'shifts';
    protected  $guarded = [];

    protected $casts = [
        "free_d" => "array"
    ];


    public function change_shift()
    {
        return $this->hasMany(change_shift::class);
    }

    public function tlbplease()
    {
        return $this->hasMany(tlbplease::class);
    }

    public function shift_week()
    {
        return $this->hasOne(shift_week::class, 'employees_id');
    }

    public function employees()
    {
        return $this->hasMany(employees::class)->whereJsonContains("shift_ids", $this->id);
    }

    public function count()
    {
        return $this->hasMany(count::class);
    }


    public function headsection()
    {
        return $this->hasMany(head_sections::class);
    }

    public function overrides()
    {
        return $this->hasMany(SupShift::class, 'shift_id');
    }
}
