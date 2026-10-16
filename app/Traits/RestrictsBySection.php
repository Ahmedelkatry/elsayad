<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait RestrictsBySection
{
    protected static function bootRestrictsBySection()
    {
        static::addGlobalScope('restrict_by_section', function (Builder $builder) {
            if (auth()->check()) {
                $user = auth()->user();
                if ($user->hasRole('رئيس قسم') && !$user->hasRole('owner')) {
                    $managed = $user->managedSectionIds();
                    
                    $table = $builder->getModel()->getTable();
                    
                    if ($table === 'employees') {
                        $builder->whereIn($table . '.section_id', $managed);
                    } else if ($table === 'sections') {
                        $builder->whereIn($table . '.id', $managed);
                    } else if (\Illuminate\Support\Facades\Schema::hasColumn($table, 'section_id')) {
                        $builder->whereIn($table . '.section_id', $managed);
                    } else if (\Illuminate\Support\Facades\Schema::hasColumn($table, 'employees_id')) {
                        $empIds = \App\Models\employees::withoutGlobalScope('restrict_by_section')->whereIn('section_id', $managed)->pluck('id');
                        $builder->whereIn($table . '.employees_id', $empIds);
                    }
                }
            }
        });
    }
}
