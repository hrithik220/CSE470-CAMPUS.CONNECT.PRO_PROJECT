<?php

namespace Database\Seeders;

use App\Models\University;
use Illuminate\Database\Seeder;

class UniversitySeeder extends Seeder
{
    public function run(): void
    {
        $universities = [
            ['name' => 'State University', 'address' => '100 University Ave', 'latitude' => 40.7128, 'longitude' => -74.0060],
            ['name' => 'Tech Institute', 'address' => '200 Innovation Blvd', 'latitude' => 37.7749, 'longitude' => -122.4194],
            ['name' => 'Liberal Arts College', 'address' => '300 Campus Dr', 'latitude' => 34.0522, 'longitude' => -118.2437],
        ];

        foreach ($universities as $uni) {
            University::create($uni);
        }
    }
}
