<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UniversitySeeder::class,
            UserSeeder::class,
            BadgeSeeder::class,
            ItemSeeder::class,
        ]);
    }
}
