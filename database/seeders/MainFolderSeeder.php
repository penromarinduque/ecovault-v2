<?php

namespace Database\Seeders;

use App\Models\MainFolder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MainFolderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        MainFolder::insert([
            [
                "id" => 1,
                "name" => "Administrative",
                "folder_key" => "administrative",
                "created_at" => now(),
                "updated_at" => now()
            ],
            
            [
                "id" => 2,
                "name" => "Permits and Registration",
                "folder_key" => "permits_and_registration",
                "created_at" => now(),
                "updated_at" => now()
            ],
        ]);
    }
}
