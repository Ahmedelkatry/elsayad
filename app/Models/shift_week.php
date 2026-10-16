<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class shift_week extends Model
{
    protected  $guarded = [];

    public function employees()
    {
        return $this->belongsTo(employees::class);
    }

    public function shift()
    {
        return $this->belongsTo(shift::class);
    }
}
