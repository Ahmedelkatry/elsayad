<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class counts_emps extends Model
{
    use \App\Traits\RestrictsBySection;
    protected $guarded = [];
}
