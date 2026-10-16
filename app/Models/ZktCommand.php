<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ZktCommand extends Model
{
    protected $fillable = ['sn', 'command', 'executed'];
}
