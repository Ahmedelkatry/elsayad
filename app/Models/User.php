<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;


class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'roles_name',
        'Status',
        'user_agent',
        'user_agent_mobile',
        'num',
    ];
    protected $appends = ['image_path'];
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'roles_name' => 'array',
            'user_agent' => 'array',
            'user_agent_mobile' => 'array',
            'password' => 'hashed',

        ];
    }



    public function getNameAttribute($value)
    {
        return ucfirst($value);
    }//end of get image path

    public function employee()
    {
        return $this->belongsTo(employees::class, 'num', 'num');
    }

    public function managedSectionIds()
    {
        $employee = $this->employee()->withoutGlobalScope('restrict_by_section')->first();
        if ($employee) {
            return $employee->managedSections()->withoutGlobalScope('restrict_by_section')->pluck('sections.id')->toArray();
        }
        return [];
    }
}
