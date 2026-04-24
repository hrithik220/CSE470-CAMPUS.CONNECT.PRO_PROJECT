<?php

namespace App\Http\Controllers\Hrithik;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\Transaction;
use App\Models\MarketplaceItem;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// ─────────────────────────────────────────────────────────────
// HRITHIK — Feature 4: Buyer Review & Rating System
// ─────────────────────────────────────────────────────────────

class ReviewController extends Controller
{
    // Show review form after completed transaction
    public function create(MarketplaceItem $item)
    {
        $transaction = Transaction::where('item_id', $item->id)
            ->where('buyer_id', Auth::id())
            ->where('status', 'completed')
            ->firstOrFail();

        // Check if already reviewed
        $existingReview = Review::where('transaction_id', $transaction->id)
            ->where('reviewer_id', Auth::id())
            ->first();

        if ($existingReview) {
            return redirect()->route('marketplace.index')
                ->with('error', 'You have already reviewed this transaction.');
        }

        $seller = User::findOrFail($item->seller_id);

        return view('marketplace.review', compact('item', 'transaction', 'seller'));
    }

    // Store the review and update seller credibility
    public function store(Request $request, MarketplaceItem $item)
    {
        $request->validate([
            'rating'  => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:500',
        ]);

        $transaction = Transaction::where('item_id', $item->id)
            ->where('buyer_id', Auth::id())
            ->where('status', 'completed')
            ->firstOrFail();

        // Create the review
        Review::create([
            'reviewer_id'    => Auth::id(),
            'reviewee_id'    => $item->seller_id,
            'transaction_id' => $transaction->id,
            'rating'         => $request->rating,
            'comment'        => $request->comment,
        ]);

        // Recalculate seller credibility score
        $seller = User::findOrFail($item->seller_id);
        $avgRating = $seller->receivedReviews()->avg('rating');
        $credibility = ($avgRating / 5) * 10;
        $seller->update(['credibility_score' => round($credibility, 1)]);

        return redirect()->route('marketplace.seller.profile', $item->seller_id)
            ->with('success', 'Review submitted successfully!');
    }

    // Show all reviews for a seller
    public function sellerReviews($sellerId)
    {
        $seller = User::findOrFail($sellerId);
        $reviews = Review::where('reviewee_id', $sellerId)
            ->with('reviewer', 'transaction.item')
            ->latest()
            ->paginate(10);

        $avgRating = $reviews->avg('rating');

        return view('marketplace.seller-reviews', compact('seller', 'reviews', 'avgRating'));
    }
}
