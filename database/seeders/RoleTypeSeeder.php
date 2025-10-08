<?php

namespace Database\Seeders;

use App\Models\RoleType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        RoleType::insert([
            [
                "name" => "Admin",
                "id" => 1
            ],
            [
                "name" => "Encoder",
                "id" => 2
            ],
            [
                "name" => "Viewer",
                "id" => 3
            ],
        ]);
    }
}
