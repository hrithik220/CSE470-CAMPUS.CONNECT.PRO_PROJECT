<?php

namespace App\Services;

use App\Models\FraudReport;
use App\Models\KarmaLog;
use App\Models\Review;
use App\Models\User;
use App\Models\Item;
use Illuminate\Support\Collection;

class FraudDetectionService
{
    /**
     * Run all fraud detection checks and return suspicious activities.
     */
    public function detectAllAnomalies(): Collection
    {
        $anomalies = collect();

        $anomalies = $anomalies->merge($this->detectFakeReviews());
        $anomalies = $anomalies->merge($this->detectSpamListings());
        $anomalies = $anomalies->merge($this->detectKarmaSpikes());

        return $anomalies;
    }

    /**
     * Detect potential fake reviews.
     * Flags users who give only 5-star reviews to the same seller.
     */
    public function detectFakeReviews(): Collection
    {
        $suspicious = Review::select('reviewer_id', 'reviewed_user_id')
            ->selectRaw('COUNT(*) as review_count')
            ->selectRaw('AVG(rating) as avg_rating')
            ->groupBy('reviewer_id', 'reviewed_user_id')
            ->having('review_count', '>=', 3)
            ->having('avg_rating', '>=', 4.8)
            ->get();

        return $suspicious->map(function ($item) {
            return (object) [
                'type' => 'fake_review',
                'description' => "User #{$item->reviewer_id} gave {$item->review_count} reviews (avg {$item->avg_rating}) to User #{$item->reviewed_user_id}",
                'user_id' => $item->reviewer_id,
                'severity' => 'medium',
            ];
        });
    }

    /**
     * Detect spam listings.
     * Flags users with excessive listings in a short period.
     */
    public function detectSpamListings(): Collection
    {
        $suspicious = Item::select('seller_id')
            ->selectRaw('COUNT(*) as listing_count')
            ->where('created_at', '>=', now()->subDay())
            ->groupBy('seller_id')
            ->having('listing_count', '>=', 10)
            ->get();

        return $suspicious->map(function ($item) {
            return (object) [
                'type' => 'spam_listing',
                'description' => "User #{$item->seller_id} posted {$item->listing_count} listings in 24 hours",
                'user_id' => $item->seller_id,
                'severity' => 'high',
            ];
        });
    }

    /**
     * Detect abnormal karma spikes.
     * Flags users with unusually high karma gains in a short period.
     */
    public function detectKarmaSpikes(): Collection
    {
        $suspicious = KarmaLog::select('user_id')
            ->selectRaw('SUM(points) as total_gained')
            ->where('points', '>', 0)
            ->where('created_at', '>=', now()->subHours(24))
            ->groupBy('user_id')
            ->having('total_gained', '>=', 100)
            ->get();

        return $suspicious->map(function ($item) {
            return (object) [
                'type' => 'karma_manipulation',
                'description' => "User #{$item->user_id} gained {$item->total_gained} karma in 24 hours",
                'user_id' => $item->user_id,
                'severity' => 'high',
            ];
        });
    }

    /**
     * Auto-create fraud reports from detected anomalies.
     */
    public function autoReport(): int
    {
        $anomalies = $this->detectAllAnomalies();
        $created = 0;

        foreach ($anomalies as $anomaly) {
            $exists = FraudReport::where('reported_user_id', $anomaly->user_id)
                ->where('type', $anomaly->type)
                ->where('status', '!=', 'resolved')
                ->exists();

            if (!$exists) {
                FraudReport::create([
                    'reported_user_id' => $anomaly->user_id,
                    'type' => $anomaly->type,
                    'reason' => $anomaly->description,
                    'status' => 'pending',
                ]);
                $created++;
            }
        }

        return $created;
    }
}
