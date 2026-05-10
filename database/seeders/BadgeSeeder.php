<?php

namespace Database\Seeders;

use App\Models\Badge;
use Illuminate\Database\Seeder;

class BadgeSeeder extends Seeder
{
    public function run(): void
    {
        $badges = [
            [
                'name' => 'Top Seller',
                'slug' => 'top-seller',
                'description' => 'Awarded for completing 10+ successful sales',
                'icon' => '🏆',
                'color' => '#FFD700',
                'karma_threshold' => 100,
            ],
            [
                'name' => 'Trusted Member',
                'slug' => 'trusted-member',
                'description' => 'Awarded for earning 50+ karma points through positive reviews',
                'icon' => '⭐',
                'color' => '#4CAF50',
                'karma_threshold' => 50,
            ],
            [
                'name' => 'Campus Hero',
                'slug' => 'campus-hero',
                'description' => 'Awarded for earning 200+ karma points and being an active community member',
                'icon' => '🦸',
                'color' => '#2196F3',
                'karma_threshold' => 200,
            ],
            [
                'name' => 'Eco Warrior',
                'slug' => 'eco-warrior',
                'description' => 'Awarded for contributing to campus sustainability by reusing 20+ items',
                'icon' => '🌿',
                'color' => '#43A047',
                'karma_threshold' => 150,
            ],
            [
                'name' => 'Rising Star',
                'slug' => 'rising-star',
                'description' => 'Awarded for reaching 25 karma points',
                'icon' => '🌟',
                'color' => '#FF9800',
                'karma_threshold' => 25,
            ],
        ];

        foreach ($badges as $badge) {
            Badge::create($badge);
        }
    }
}
