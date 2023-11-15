<?php

namespace Database\Seeders;

use App\Models\Level;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            ["name" => "SuperAdmin"],
            ["name" => "Basic"],
            ["name" => "Student"],
            ["name" => "Artist"],
            ["name" => "ShinyArtist"],
            ["name" => "Master"],
        ];
        \App\Models\Role::insert($roles);


        $user = \App\Models\User::factory()->create([
            'name' => 'Melissa Rahimi',
            'email' => 'support@melissarahimi.com',
        ]);


         \App\Models\User::factory(20)->create();

         $levels = [
             ["title"=>"Student", "sort_order" => 1],
             ["title"=>"Artist", "sort_order" => 2],
             ["title"=>"Shiny artist", "sort_order" => 3],
             ["title"=>"Master", "sort_order" => 5],
         ];
         Level::insert($levels);
    }
}
