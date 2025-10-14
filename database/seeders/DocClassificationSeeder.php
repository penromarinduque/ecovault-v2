<?php

namespace Database\Seeders;

use App\Models\DocClassification;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DocClassificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DocClassification::insert([
            [
                "id" => 1,
                "name" => "Highly Technical",
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "id" => 2,
                "name" => "Simple",
                "created_at" => now(),
                "updated_at" => now()
            ]
        ]);
    }
}
