<?php

namespace App\Http\Controllers\Hrithik;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\MarketplaceItem;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// ─────────────────────────────────────────────────────────────
// HRITHIK — Feature 3: Seller Credibility Score & Transaction History
// ─────────────────────────────────────────────────────────────

class TransactionController extends Controller
{
    // Show seller profile with credibility score + transaction history
    public function sellerProfile($sellerId)
    {
        $seller = User::findOrFail($sellerId);

        // Get all completed transactions for this seller
        $transactions = Transaction::where('seller_id', $sellerId)
            ->where('status', 'completed')
            ->with(['item', 'buyer', 'review'])
            ->latest()
            ->paginate(10);

        // Calculate average rating from reviews
        $avgRating = $seller->receivedReviews()->avg('rating');
        $totalReviews = $seller->receivedReviews()->count();
        $totalSales = Transaction::where('seller_id', $sellerId)
            ->where('status', 'completed')
            ->count();

        // Active listings
        $activeListings = MarketplaceItem::where('seller_id', $sellerId)
            ->where('status', 'available')
            ->count();

        return view('marketplace.seller-profile', compact(
            'seller',
            'transactions',
            'avgRating',
            'totalReviews',
            'totalSales',
            'activeListings'
        ));
    }

    // Mark a transaction as completed (buyer confirms pickup)
    public function complete(Request $request, MarketplaceItem $item)
    {
        $transaction = Transaction::where('item_id', $item->id)
            ->where('buyer_id', Auth::id())
            ->where('status', 'pending')
            ->firstOrFail();

        $transaction->update(['status' => 'completed']);

        // Update item status to sold
        $item->update(['status' => 'sold']);

        // Update seller credibility score
        $this->updateCredibilityScore($transaction->seller_id);

        return redirect()->route('marketplace.review.create', $item)
            ->with('success', 'Transaction completed! Please leave a review.');
    }

    // Create a pending transaction when buyer requests item
    public function store(Request $request, MarketplaceItem $item)
    {
        // Check if transaction already exists
        $existing = Transaction::where('item_id', $item->id)
            ->where('buyer_id', Auth::id())
            ->first();

        if ($existing) {
            return redirect()->route('marketplace.show', $item)
                ->with('error', 'You already have a pending transaction for this item.');
        }

        Transaction::create([
            'item_id'   => $item->id,
            'buyer_id'  => Auth::id(),
            'seller_id' => $item->seller_id,
            'status'    => 'pending',
        ]);

        return redirect()->route('marketplace.show', $item)
            ->with('success', 'Purchase request sent! Contact the seller via chat.');
    }

    // Update seller credibility score based on reviews
    private function updateCredibilityScore($sellerId)
    {
        $seller = User::findOrFail($sellerId);
        $avgRating = $seller->receivedReviews()->avg('rating');

        if ($avgRating) {
            // Scale: 1-5 stars → credibility 0-10
            $credibility = ($avgRating / 5) * 10;
            $seller->update(['credibility_score' => round($credibility, 1)]);
        }
    }
}
