<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class count extends Model
{
    protected  $guarded = [];

    public function employees(){
        return $this->belongsTo(employees::class);
    }

    public function shiftt(){
        return $this->belongsTo(shift::class, 'shift_id');
    }


    public function section(){
        return $this->belongsTo(section::class);
    }




}
