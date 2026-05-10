<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Karma Point Values
    |--------------------------------------------------------------------------
    |
    | Points awarded for various user actions across the platform.
    |
    */

    'points' => [
        // Ride Sharing
        'ride_share' => (int) env('KARMA_RIDE_SHARE', 10),
        'ride_join' => (int) env('KARMA_RIDE_JOIN', 5),

        // Marketplace
        'listing_create' => (int) env('KARMA_LISTING_CREATE', 3),
        'listing_sell' => (int) env('KARMA_LISTING_SELL', 8),

        // Tutoring
        'tutor_session' => (int) env('KARMA_TUTOR_SESSION', 15),

        // Forum
        'forum_post' => (int) env('KARMA_FORUM_POST', 2),
        'forum_answer' => (int) env('KARMA_FORUM_ANSWER', 5),
        'forum_upvote_received' => (int) env('KARMA_FORUM_UPVOTE_RECEIVED', 1),

        // General
        'review_given' => (int) env('KARMA_REVIEW_GIVEN', 2),
    ],

    /*
    |--------------------------------------------------------------------------
    | Badge Definitions
    |--------------------------------------------------------------------------
    */

    'badges' => [
        'top_tutor' => [
            'name' => 'Top Tutor',
            'description' => 'Awarded to the tutor with the most sessions in a month',
            'icon' => '🎓',
        ],
        'ride_hero' => [
            'name' => 'Ride Hero',
            'description' => 'Awarded for sharing 10+ rides in a month',
            'icon' => '🚗',
        ],
        'best_seller' => [
            'name' => 'Best Seller',
            'description' => 'Awarded for 10+ successful marketplace transactions',
            'icon' => '⭐',
        ],
        'eco_warrior' => [
            'name' => 'Eco Warrior',
            'description' => 'Awarded for significant CO2 savings through ride sharing',
            'icon' => '🌱',
        ],
        'knowledge_guru' => [
            'name' => 'Knowledge Guru',
            'description' => 'Awarded for 50+ helpful forum answers',
            'icon' => '🧠',
        ],
        'karma_king' => [
            'name' => 'Karma King',
            'description' => 'Top karma points earner of the month',
            'icon' => '👑',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Sustainability Metrics
    |--------------------------------------------------------------------------
    */

    'sustainability' => [
        'co2_per_km_saved' => 0.21, // kg CO2 saved per km shared ride
        'avg_ride_distance_km' => 8, // average campus ride distance
    ],
];
