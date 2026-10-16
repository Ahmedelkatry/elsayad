<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class change_shift extends Model
{
    use \App\Traits\RestrictsBySection;
    protected  $guarded = [];

    public function shift()
    {
        return $this->belongsTo(shift::class);
    }


    public function employees()
    {
        return $this->belongsTo(employees::class, 'employees_id');
    }
}
