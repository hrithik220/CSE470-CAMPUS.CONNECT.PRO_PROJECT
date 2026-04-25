<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KarmaPoint extends Model
{
    protected $fillable = [
        'user_id', 'points', 'action', 'module',
        'description', 'reference_id', 'reference_type',
    ];

    // ── Relationships ─────────────────────────────────────────────────────────

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // ── Constants: action → points map ────────────────────────────────────────

    public const ACTION_MAP = [
        // Rides module
        'ride_offered'          => 10,
        'ride_completed_driver' => 15,
        'ride_completed_rider'  => 5,
        'ride_cancelled_driver' => -10,
        'ride_cancelled_rider'  => -5,

        // Marketplace module
        'item_listed'           => 5,
        'item_sold'             => 10,
        'item_purchased'        => 3,
        'item_review_given'     => 5,
        'item_review_received'  => 8,

        // Tutoring module
        'session_offered'       => 10,
        'session_completed_tutor'  => 20,
        'session_completed_student'=> 5,
        'session_reviewed'      => 5,

        // Community / general
        'profile_completed'     => 20,
        'first_login'           => 10,
        'report_resolved'       => 15,
        'helpful_flag'          => 5,
        'spam_flag'             => -20,
    ];

    // ── Badge thresholds ──────────────────────────────────────────────────────

    public const BADGES = [
        0    => 'Newcomer',
        50   => 'Member',
        150  => 'Contributor',
        300  => 'Helper',
        500  => 'Trusted',
        800  => 'Champion',
        1200 => 'Legend',
    ];

    public static function badgeForTotal(int $total): string
    {
        $badge = 'Newcomer';
        foreach (self::BADGES as $threshold => $name) {
            if ($total >= $threshold) {
                $badge = $name;
            }
        }
        return $badge;
    }
}
