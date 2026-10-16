<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class disemp extends Model
{
    use \App\Traits\RestrictsBySection, \App\Traits\NewestFirst;
    protected  $guarded = [];
public function disemp(){
    $disemp= DB::table("disemp")->count();
    return $disemp;
}


    public function employees(){
        return $this->belongsTo(employees::class);
    }

    public function count(){
        return $this->hasMany(count::class);
    }

    public function section(){
        return $this->belongsTo(section::class);
    }
}
