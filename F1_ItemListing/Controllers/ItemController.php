<?php

namespace App\Http\Controllers\Marketplace;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreItemRequest;
use App\Http\Requests\UpdateItemRequest;
use App\Models\Item;
use App\Models\ItemImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $query = Item::available()->with(['seller', 'images']);

        // Apply filters
        $query->search($request->keyword)
              ->byCategory($request->category)
              ->byCondition($request->condition)
              ->byPriceRange($request->min_price, $request->max_price);

        // Sort
        $sortBy = $request->get('sort', 'latest');
        $query = match ($sortBy) {
            'price_low' => $query->orderBy('price', 'asc'),
            'price_high' => $query->orderBy('price', 'desc'),
            'popular' => $query->orderByDesc('views_count'),
            default => $query->latest(),
        };

        $items = $query->paginate(12)->appends($request->query());
        $categories = Item::CATEGORIES;
        $conditions = Item::CONDITIONS;

        return view('marketplace.index', compact('items', 'categories', 'conditions'));
    }

    public function create()
    {
        $categories = Item::CATEGORIES;
        $conditions = Item::CONDITIONS;
        return view('marketplace.create', compact('categories', 'conditions'));
    }

    public function store(StoreItemRequest $request)
    {
        $item = Item::create([
            'seller_id' => auth()->id(),
            'title' => $request->title,
            'description' => $request->description,
            'price' => $request->price,
            'category' => $request->category,
            'condition' => $request->condition,
        ]);

        // Handle image uploads
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('items', 'public');
                $item->images()->create([
                    'image_path' => $path,
                    'sort_order' => $index,
                ]);
            }
        }

        return redirect()->route('marketplace.show', $item)
            ->with('success', 'Item listed successfully!');
    }

    public function show(Item $item)
    {
        $item->incrementViews();
        $item->load(['seller', 'images', 'reviews.reviewer']);

        $sellerStats = [
            'avg_rating' => $item->seller->average_rating,
            'total_sales' => $item->seller->total_sales,
            'karma' => $item->seller->karma_points,
        ];

        $relatedItems = Item::available()
            ->where('category', $item->category)
            ->where('id', '!=', $item->id)
            ->with('images')
            ->take(4)
            ->get();

        return view('marketplace.show', compact('item', 'sellerStats', 'relatedItems'));
    }

    public function edit(Item $item)
    {
        $this->authorize('update', $item);
        $categories = Item::CATEGORIES;
        $conditions = Item::CONDITIONS;
        $item->load('images');
        return view('marketplace.edit', compact('item', 'categories', 'conditions'));
    }

    public function update(UpdateItemRequest $request, Item $item)
    {
        $item->update($request->only(['title', 'description', 'price', 'category', 'condition']));

        // Remove selected images
        if ($request->has('remove_images')) {
            foreach ($request->remove_images as $imageId) {
                $image = ItemImage::find($imageId);
                if ($image && $image->item_id === $item->id) {
                    Storage::disk('public')->delete($image->image_path);
                    $image->delete();
                }
            }
        }

        // Add new images
        if ($request->hasFile('images')) {
            $maxOrder = $item->images()->max('sort_order') ?? 0;
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('items', 'public');
                $item->images()->create([
                    'image_path' => $path,
                    'sort_order' => $maxOrder + $index + 1,
                ]);
            }
        }

        return redirect()->route('marketplace.show', $item)
            ->with('success', 'Item updated successfully!');
    }

    public function destroy(Item $item)
    {
        if (auth()->id() !== $item->seller_id && !auth()->user()->isAdmin()) {
            abort(403);
        }

        // Delete images from storage
        foreach ($item->images as $image) {
            Storage::disk('public')->delete($image->image_path);
        }

        $item->delete();

        return redirect()->route('marketplace.index')
            ->with('success', 'Item removed successfully.');
    }

    public function myListings()
    {
        $items = Item::where('seller_id', auth()->id())
            ->with('images')
            ->withCount('conversations')
            ->latest()
            ->paginate(12);

        return view('marketplace.my-listings', compact('items'));
    }
}
