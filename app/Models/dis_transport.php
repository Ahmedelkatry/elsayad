<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class dis_transport extends Model
{
    use \App\Traits\RestrictsBySection, \App\Traits\NewestFirst;
    protected  $guarded = [];


    public function employees()
    {
        return $this->belongsTo(employees::class);
    }
}
