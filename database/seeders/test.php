<?php

namespace Database\Seeders;

use App\Models\employees;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as faker;

class test extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
                 $faker= Faker::create();
 foreach(range(1,10) as $index){
    $user = employees::create([
"num" => 1,
"name"=>$faker->name,
'email'=>$faker->unique()->safeEmail(),
"password"=>bcrypt('aaaa1111'),
"section_id"=>1,
"shift_id"=>1,
"kabd"=>10000,
    ]);
 }

    }
}
