<?php

namespace App\Http\Controllers\Hrithik;

use App\Http\Controllers\Controller;
use App\Models\MarketplaceItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

// ─────────────────────────────────────────────────────────────
// HRITHIK — Feature 1: Item Listing (MarketplaceItem CRUD)
// ─────────────────────────────────────────────────────────────

class MarketplaceItemController extends Controller
{
    // Show all available items (with search & filter support)
    public function index(Request $request)
    {
        $query = MarketplaceItem::with('seller')
            ->where('status', 'available');

        // Search by title
        if ($request->has('search') && $request->search != '') {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        // Filter by category
        if ($request->has('category') && $request->category != '') {
            $query->where('category', $request->category);
        }

        // Filter by condition
        if ($request->has('condition') && $request->condition != '') {
            $query->where('condition_rating', $request->condition);
        }

        // Filter by max price
        if ($request->has('max_price') && $request->max_price != '') {
            $query->where('price', '<=', $request->max_price);
        }

        $items = $query->latest()->paginate(12);

        return view('marketplace.index', compact('items'));
    }

    // Show single item detail
    public function show(MarketplaceItem $item)
    {
        $item->load('seller', 'chatMessages');
        return view('marketplace.show', compact('item'));
    }

    // Show create form
    public function create()
    {
        return view('marketplace.create');
    }

    // Store new item listing
    public function store(Request $request)
    {
        $request->validate([
            'title'            => 'required|string|max:255',
            'description'      => 'nullable|string',
            'price'            => 'required|numeric|min:0',
            'condition_rating' => 'required|in:new,like_new,good,fair,poor',
            'category'         => 'required|string|max:100',
            'photos'           => 'nullable|array',
            'photos.*'         => 'image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $photoPaths = [];

        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('marketplace/photos', 'public');
                $photoPaths[] = $path;
            }
        }

        MarketplaceItem::create([
            'seller_id'        => Auth::id(),
            'title'            => $request->title,
            'description'      => $request->description,
            'price'            => $request->price,
            'condition_rating' => $request->condition_rating,
            'category'         => $request->category,
            'photos'           => $photoPaths,
            'status'           => 'available',
        ]);

        return redirect()->route('marketplace.index')
            ->with('success', 'Item listed successfully!');
    }

    // Show edit form
    public function edit(MarketplaceItem $item)
    {
        // Only the seller can edit
        if ($item->seller_id !== Auth::id()) {
            abort(403);
        }
        return view('marketplace.edit', compact('item'));
    }

    // Update item
    public function update(Request $request, MarketplaceItem $item)
    {
        if ($item->seller_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'title'            => 'required|string|max:255',
            'description'      => 'nullable|string',
            'price'            => 'required|numeric|min:0',
            'condition_rating' => 'required|in:new,like_new,good,fair,poor',
            'category'         => 'required|string|max:100',
            'status'           => 'required|in:available,sold,reserved',
        ]);

        $item->update($request->only([
            'title', 'description', 'price',
            'condition_rating', 'category', 'status',
        ]));

        return redirect()->route('marketplace.show', $item)
            ->with('success', 'Item updated successfully!');
    }

    // Delete item
    public function destroy(MarketplaceItem $item)
    {
        if ($item->seller_id !== Auth::id()) {
            abort(403);
        }
        $item->delete();
        return redirect()->route('marketplace.index')
            ->with('success', 'Item deleted.');
    }

    // Show logged-in user's own listings
    public function myListings()
    {
        $items = MarketplaceItem::where('seller_id', Auth::id())
            ->latest()
            ->paginate(10);
        return view('marketplace.my-listings', compact('items'));
    }
}
