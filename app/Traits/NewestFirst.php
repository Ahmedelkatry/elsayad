<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait NewestFirst
{
    protected static function bootNewestFirst()
    {
        static::addGlobalScope('newest_first', function (Builder $builder) {
            $builder->orderBy($builder->getModel()->getTable() . '.id', 'desc');
        });
    }
}
