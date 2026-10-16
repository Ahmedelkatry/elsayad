<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class tlbplease extends Model
{
    use \App\Traits\RestrictsBySection;
  protected  $guarded = [];

  public function employees()
  {
    return $this->belongsTo(employees::class);
  }
  public function shift()
  {
    return $this->belongsTo(shift::class);
  }
}
