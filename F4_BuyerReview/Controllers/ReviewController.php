<?php

namespace App\Http\Controllers\Marketplace;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreReviewRequest;
use App\Models\Review;
use App\Models\Transaction;
use App\Notifications\ReviewReceivedNotification;
use App\Services\KarmaService;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function create(Transaction $transaction)
    {
        // Only buyer can review, only after completion, only once
        abort_unless($transaction->buyer_id === auth()->id(), 403);
        abort_unless($transaction->status === 'completed', 403);

        $alreadyReviewed = Review::where('transaction_id', $transaction->id)
            ->where('reviewer_id', auth()->id())
            ->exists();

        if ($alreadyReviewed) {
            return redirect()->route('transactions.show', $transaction)
                ->with('error', 'You have already reviewed this transaction.');
        }

        $transaction->load(['item', 'seller']);
        return view('marketplace.review', compact('transaction'));
    }

    public function store(StoreReviewRequest $request, Transaction $transaction)
    {
        abort_unless($transaction->buyer_id === auth()->id(), 403);
        abort_unless($transaction->status === 'completed', 403);

        $alreadyReviewed = Review::where('transaction_id', $transaction->id)
            ->where('reviewer_id', auth()->id())
            ->exists();

        if ($alreadyReviewed) {
            return redirect()->route('transactions.show', $transaction)
                ->with('error', 'You have already reviewed this transaction.');
        }

        $review = Review::create([
            'transaction_id'   => $transaction->id,
            'item_id'          => $transaction->item_id,
            'reviewer_id'      => auth()->id(),
            'reviewed_user_id' => $transaction->seller_id,
            'rating'           => $request->rating,
            'comment'          => $request->comment,
        ]);

        // Award karma to seller based on rating
        $karmaService = app(KarmaService::class);
        $karmaService->awardReviewKarma($transaction->seller, $review->rating);

        // Notify seller
        try {
            $transaction->seller->notify(new ReviewReceivedNotification($review));
        } catch (\Exception $e) {
            // Fail silently — notification is non-critical
        }

        return redirect()->route('transactions.show', $transaction)
            ->with('success', 'Review submitted successfully! Thank you for your feedback.');
    }
}
