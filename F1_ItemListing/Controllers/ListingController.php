<?php

namespace App\Http\Controllers\Marketplace;

use App\Http\Controllers\Controller;
use App\Models\Listing;
use App\Models\ListingImage;
use App\Models\Transaction;
use App\Models\ListingReview;
use App\Events\ListingCreated;
use App\Events\TransactionCompleted;
use App\Events\ReviewSubmitted;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ListingController extends Controller
{
    /**
     * Display marketplace listings with search/filter.
     */
    public function index(Request $request)
    {
        $listings = Listing::with(['seller', 'images'])
            ->active()
            ->search(
                $request->input('search'),
                $request->input('category'),
                $request->input('condition'),
                $request->input('min_price') ? (float) $request->input('min_price') : null,
                $request->input('max_price') ? (float) $request->input('max_price') : null
            )
            ->latest()
            ->paginate(12);

        $categories = Listing::CATEGORIES;
        $conditions = Listing::CONDITIONS;

        return view('marketplace.index', compact('listings', 'categories', 'conditions'));
    }

    /**
     * Show listing creation form.
     */
    public function create()
    {
        $categories = Listing::CATEGORIES;
        $conditions = Listing::CONDITIONS;
        return view('marketplace.create', compact('categories', 'conditions'));
    }

    /**
     * Store a new listing.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:2000',
            'price' => 'required|numeric|min:0',
            'condition' => 'required|in:new,like_new,good,fair,poor',
            'category' => 'required|in:' . implode(',', array_keys(Listing::CATEGORIES)),
            'images' => 'nullable|array|max:5',
            'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $listing = Listing::create([
            'user_id' => auth()->id(),
            'title' => $validated['title'],
            'description' => $validated['description'],
            'price' => $validated['price'],
            'condition' => $validated['condition'],
            'category' => $validated['category'],
        ]);

        // Handle image uploads
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('listings/' . $listing->id, 'public');
                ListingImage::create([
                    'listing_id' => $listing->id,
                    'path' => $path,
                    'order' => $index,
                ]);
            }
        }

        event(new ListingCreated($listing));

        return redirect()->route('marketplace.show', $listing)
            ->with('success', 'Listing created successfully!');
    }

    /**
     * Show listing details.
     */
    public function show(Listing $listing)
    {
        $listing->load(['seller', 'images', 'transaction.reviews']);
        $listing->increment('views_count');

        $sellerRating = ListingReview::where('reviewee_id', $listing->user_id)->avg('rating');
        $sellerListings = Listing::where('user_id', $listing->user_id)
            ->where('id', '!=', $listing->id)
            ->active()
            ->limit(4)
            ->get();

        return view('marketplace.show', compact('listing', 'sellerRating', 'sellerListings'));
    }

    /**
     * Edit listing form.
     */
    public function edit(Listing $listing)
    {
        if ($listing->user_id !== auth()->id()) {
            abort(403);
        }

        $categories = Listing::CATEGORIES;
        $conditions = Listing::CONDITIONS;
        return view('marketplace.edit', compact('listing', 'categories', 'conditions'));
    }

    /**
     * Update a listing.
     */
    public function update(Request $request, Listing $listing)
    {
        if ($listing->user_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:2000',
            'price' => 'required|numeric|min:0',
            'condition' => 'required|in:new,like_new,good,fair,poor',
            'category' => 'required|in:' . implode(',', array_keys(Listing::CATEGORIES)),
            'images' => 'nullable|array|max:5',
            'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $listing->update($validated);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('listings/' . $listing->id, 'public');
                ListingImage::create([
                    'listing_id' => $listing->id,
                    'path' => $path,
                    'order' => $listing->images()->count() + $index,
                ]);
            }
        }

        return redirect()->route('marketplace.show', $listing)
            ->with('success', 'Listing updated successfully!');
    }

    /**
     * Mark listing as sold and create transaction.
     */
    public function markSold(Request $request, Listing $listing)
    {
        if ($listing->user_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'buyer_id' => 'required|exists:users,id',
        ]);

        $transaction = Transaction::create([
            'listing_id' => $listing->id,
            'buyer_id' => $validated['buyer_id'],
            'seller_id' => auth()->id(),
            'amount' => $listing->price,
            'status' => 'completed',
        ]);

        $listing->update(['status' => 'sold']);

        event(new TransactionCompleted($transaction));

        return back()->with('success', 'Item marked as sold!');
    }

    /**
     * Submit a review for a transaction.
     */
    public function review(Request $request, Transaction $transaction)
    {
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:500',
        ]);

        $revieweeId = $transaction->buyer_id === auth()->id()
            ? $transaction->seller_id
            : $transaction->buyer_id;

        $review = ListingReview::create([
            'transaction_id' => $transaction->id,
            'reviewer_id' => auth()->id(),
            'reviewee_id' => $revieweeId,
            'rating' => $validated['rating'],
            'comment' => $validated['comment'],
        ]);

        event(new ReviewSubmitted($review, 'listing'));

        return back()->with('success', 'Review submitted!');
    }

    /**
     * Delete a listing.
     */
    public function destroy(Listing $listing)
    {
        if ($listing->user_id !== auth()->id()) {
            abort(403);
        }

        // Delete images from storage
        foreach ($listing->images as $image) {
            Storage::disk('public')->delete($image->path);
        }

        $listing->update(['status' => 'removed']);

        return redirect()->route('marketplace.index')
            ->with('success', 'Listing removed.');
    }
}
