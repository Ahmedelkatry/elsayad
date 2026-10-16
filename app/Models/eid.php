<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class eid extends Model
{
    use \App\Traits\NewestFirst;
    protected  $guarded = [];
}
