<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class hafz extends Model
{
    use \App\Traits\NewestFirst;

    protected  $guarded = [];


    public function employees()
    {
        return $this->belongsTo(employees::class);
    }

    public function section()
    {
        return $this->belongsTo(section::class);
    }

    public function count()
    {
        return $this->hasMany(count::class);
    }
}
