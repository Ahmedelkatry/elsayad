<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class head_sections extends Model
{



    protected  $guarded = [];



    public function shift()
    {
        return $this->belongsTo(shift::class);
    }
    public function section()
    {
        return $this->belongsTo(section::class);
    }
}
