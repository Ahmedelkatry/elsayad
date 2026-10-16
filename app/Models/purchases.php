<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class purchases extends Model
{
    use \App\Traits\NewestFirst;
    protected  $guarded = [];

    public function employees()
    {
        return $this->belongsTo(employees::class);
    }
}
