<?php

namespace Database\Seeders;

use App\Models\CentralAdmin;
use App\Models\CentralAdminRole;
use Illuminate\Database\Seeder;

class CentralAdminSeeder extends Seeder
{
    public function run(): void
    {
        foreach (config('central_admin.roles') as $roleData) {
            CentralAdminRole::updateOrCreate(
                ['slug' => $roleData['slug']],
                [
                    'name' => $roleData['name'],
                    'permissions' => $roleData['permissions'],
                ]
            );
        }

        $superAdminRole = CentralAdminRole::where('slug', 'super_admin')->first();

        if (! $superAdminRole) {
            return;
        }

        CentralAdmin::updateOrCreate(
            ['email' => config('central_admin.email')],
            [
                'name' => 'مدير النظام',
                'password' => config('central_admin.password'),
                'central_admin_role_id' => $superAdminRole->id,
                'is_active' => true,
            ]
        );
    }
}
