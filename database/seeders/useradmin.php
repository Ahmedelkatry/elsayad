<?php

namespace Database\Seeders;

use App\Models\User;
use Faker\Factory as faker;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class useradmin extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

 
 
        // $faker= Faker::create();
// foreach(range(1,10) as $index){
        // $user = User::create([
            // 'name' => $faker->name,
            // 'email' => $faker->unique()->safeEmail(),
            // 'password' => bcrypt('aaaa1111'),
            // 'roles_name' => ["owner"],
            // 'Status' => "مفعل",
        //   ]);
// 
        // }
// 





        $user = User::create([
            'name' => 'ahmed elkatry',
            'email' => 'katryana22@gmail.com',
            'password' => bcrypt('aaaa2222'),
            'roles_name' => ["owner"],
            'Status' => "مفعل",
        ]);
        $role = Role::create(['name' => 'owner']);
        $permissions = Permission::pluck('id', 'id')->all();
        $role->syncPermissions($permissions);
        $user->assignRole([$role->id]);
    }
}
