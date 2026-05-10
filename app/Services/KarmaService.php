<?php

namespace App\Services;

use App\Models\Badge;
use App\Models\KarmaLog;
use App\Models\Review;
use App\Models\Transaction;
use App\Models\User;

class KarmaService
{
    /**
     * Award karma for a completed sale.
     */
    public function awardForSale(Transaction $transaction): void
    {
        $seller = $transaction->seller;
        $seller->addKarma(10, 'sale_completed', "Completed sale of \"{$transaction->item->title}\"", $transaction);
        Badge::checkAndAward($seller);
    }

    /**
     * Award karma based on a review rating.
     */
    public function awardForReview(Review $review): void
    {
        $reviewedUser = $review->reviewedUser;
        $points = $this->calculateReviewPoints($review->rating);
        $reviewedUser->addKarma($points, 'review_received', "Received a {$review->rating}-star review", $review);
        Badge::checkAndAward($reviewedUser);
    }

    /**
     * Calculate points based on rating (1-5 stars → 1-5 points).
     */
    private function calculateReviewPoints(int $rating): int
    {
        return max(1, min(5, $rating));
    }

    /**
     * Award karma to a seller based on their review rating (1-5 → 1-5 points).
     * Called by ReviewController after a review is submitted.
     */
    public function awardReviewKarma(User $seller, int $rating): void
    {
        $points = max(1, min(5, $rating));
        $seller->addKarma($points, 'review_received', "Received a {$rating}-star review");
        Badge::checkAndAward($seller);
    }

    /**
     * Apply fraud penalty.
     */
    public function applyFraudPenalty(User $user, int $points, string $reason): void
    {
        $user->deductKarma($points, 'fraud_penalty', $reason);
    }

    /**
     * Admin manual adjustment.
     */
    public function adminAdjust(User $user, int $points, string $reason): void
    {
        if ($points > 0) {
            $user->addKarma($points, 'admin_adjustment', $reason);
        } else {
            $user->deductKarma(abs($points), 'admin_adjustment', $reason);
        }
        Badge::checkAndAward($user);
    }

    /**
     * Get leaderboard for a specific month.
     */
    public function getMonthlyLeaderboard(int $month = null, int $year = null, int $limit = 20): \Illuminate\Support\Collection
    {
        $month = $month ?? now()->month;
        $year = $year ?? now()->year;

        return KarmaLog::select('user_id')
            ->selectRaw('SUM(points) as total_points')
            ->whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->where('points', '>', 0)
            ->groupBy('user_id')
            ->orderByDesc('total_points')
            ->limit($limit)
            ->with('user')
            ->get()
            ->map(function ($log) {
                return (object) [
                    'user' => User::find($log->user_id),
                    'total_points' => $log->total_points,
                ];
            });
    }

    /**
     * Get all-time leaderboard.
     */
    public function getAllTimeLeaderboard(int $limit = 20): \Illuminate\Database\Eloquent\Collection
    {
        return User::active()
            ->where('karma_points', '>', 0)
            ->orderByDesc('karma_points')
            ->limit($limit)
            ->get();
    }
}
