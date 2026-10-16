<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupShift extends Model
{
    protected $guarded = [];

    public function baseShift()
    {
        return $this->belongsTo(shift::class, 'shift_id');
    }

    public function overrideShift()
    {
        return $this->belongsTo(shift::class, 'override_shift_id');
    }
}
