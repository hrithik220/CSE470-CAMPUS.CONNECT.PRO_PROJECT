@extends('layouts.app')
@section('title', $item->title . ' — Marketplace')
@section('page_title', 'Item Details')

@section('content')
<div class="max-w-5xl mx-auto fade-in">
    <div class="grid lg:grid-cols-5 gap-6">
        {{-- Images --}}
        <div class="lg:col-span-3 space-y-4">
            <div class="glass rounded-xl overflow-hidden">
                <div class="aspect-[4/3] bg-surface-800">
                    @if($item->images->count())
                    <img id="mainImage" src="{{ asset('storage/' . $item->images->first()->image_path) }}" class="w-full h-full object-contain" alt="{{ $item->title }}">
                    @else
                    <div class="w-full h-full flex items-center justify-center text-brand-400">
                        <i data-lucide="package" class="w-20 h-20 opacity-30"></i>
                    </div>
                    @endif
                </div>
                @if($item->images->count() > 1)
                <div class="flex gap-2 p-3 overflow-x-auto">
                    @foreach($item->images as $img)
                    <img src="{{ asset('storage/' . $img->image_path) }}" onclick="document.getElementById('mainImage').src=this.src"
                        class="w-16 h-16 rounded-lg object-cover cursor-pointer border-2 border-transparent hover:border-brand-500 transition flex-shrink-0" alt="">
                    @endforeach
                </div>
                @endif
            </div>
            {{-- Description --}}
            <div class="glass rounded-xl p-5">
                <h3 class="font-semibold mb-3">Description</h3>
                <p class="text-gray-300 text-sm leading-relaxed whitespace-pre-line">{{ $item->description }}</p>
            </div>
            {{-- Reviews --}}
            <div class="glass rounded-xl p-5">
                <h3 class="font-semibold mb-3">Seller Reviews</h3>
                @forelse($item->seller->reviewsReceived()->with('reviewer')->latest()->take(5)->get() as $review)
                <div class="p-3 rounded-lg bg-white/5 mb-2">
                    <div class="flex items-center gap-2 mb-1">
                        <img src="{{ $review->reviewer->avatar_url }}" class="w-6 h-6 rounded-full" alt="">
                        <span class="text-sm font-medium">{{ $review->reviewer->name }}</span>
                        <div class="text-yellow-400 text-sm">{!! $review->stars_html !!}</div>
                    </div>
                    <p class="text-xs text-gray-400">{{ $review->comment }}</p>
                </div>
                @empty
                <p class="text-gray-500 text-sm">No reviews yet.</p>
                @endforelse
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="lg:col-span-2 space-y-4">
            <div class="glass rounded-xl p-5">
                <span class="inline-block px-2 py-1 text-[10px] font-bold uppercase rounded-md {{ $item->condition_badge_color }} text-white mb-3">{{ $item->condition_label }}</span>
                <h1 class="text-xl font-bold mb-1">{{ $item->title }}</h1>
                <p class="text-xs text-gray-500 mb-3">{{ $item->category_label }} · {{ $item->views_count }} views</p>
                <p class="text-3xl font-bold text-brand-400 mb-5">৳{{ number_format($item->price, 2) }}</p>

                @if($item->seller_id !== auth()->id() && $item->status === 'available')
                <div class="space-y-2">
                    <form method="POST" action="{{ route('transactions.initiate', $item) }}">
                        @csrf
                        <button type="submit" class="w-full py-3 bg-brand-600 hover:bg-brand-500 text-white font-semibold rounded-xl transition shadow-lg shadow-brand-600/25">Buy Now</button>
                    </form>
                    <form method="POST" action="{{ route('chat.start', $item) }}">
                        @csrf
                        <button type="submit" class="w-full py-3 bg-white/5 hover:bg-white/10 border border-white/10 text-white font-medium rounded-xl transition flex items-center justify-center gap-2">
                            <i data-lucide="message-square" class="w-5 h-5"></i> Message Seller
                        </button>
                    </form>
                </div>
                @elseif($item->seller_id === auth()->id())
                <div class="flex gap-2">
                    <a href="{{ route('marketplace.edit', $item) }}" class="flex-1 py-3 text-center bg-white/5 hover:bg-white/10 border border-white/10 text-white font-medium rounded-xl transition">Edit</a>
                    <form method="POST" action="{{ route('marketplace.destroy', $item) }}" class="flex-1" onsubmit="return confirm('Delete this item?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="w-full py-3 bg-red-500/10 hover:bg-red-500/20 border border-red-500/20 text-red-400 font-medium rounded-xl transition">Delete</button>
                    </form>
                </div>
                @else
                <div class="p-3 rounded-lg bg-yellow-500/10 border border-yellow-500/20 text-yellow-400 text-sm text-center">
                    This item is {{ $item->status }}.
                </div>
                @endif
            </div>

            {{-- Seller Card --}}
            <div class="glass rounded-xl p-5">
                <h3 class="text-xs uppercase tracking-widest text-gray-500 font-semibold mb-3">Seller</h3>
                <a href="{{ route('user.profile', $item->seller) }}" class="flex items-center gap-3 mb-4 hover:opacity-80 transition">
                    <img src="{{ $item->seller->avatar_url }}" class="w-12 h-12 rounded-full ring-2 ring-brand-500/30" alt="">
                    <div>
                        <p class="font-semibold">{{ $item->seller->name }}</p>
                        <p class="text-xs text-gray-500">Member since {{ $item->seller->created_at->format('M Y') }}</p>
                    </div>
                </a>
                <div class="grid grid-cols-3 gap-2 text-center">
                    <div class="p-2 rounded-lg bg-white/5"><p class="text-lg font-bold text-brand-400">{{ number_format($sellerStats['avg_rating'], 1) }}</p><p class="text-[10px] text-gray-500">Rating</p></div>
                    <div class="p-2 rounded-lg bg-white/5"><p class="text-lg font-bold text-green-400">{{ $sellerStats['total_sales'] }}</p><p class="text-[10px] text-gray-500">Sales</p></div>
                    <div class="p-2 rounded-lg bg-white/5"><p class="text-lg font-bold text-amber-400">{{ $sellerStats['karma'] }}</p><p class="text-[10px] text-gray-500">Karma</p></div>
                </div>
            </div>

            {{-- Related --}}
            @if($relatedItems->count())
            <div class="glass rounded-xl p-5">
                <h3 class="text-xs uppercase tracking-widest text-gray-500 font-semibold mb-3">Similar Items</h3>
                @foreach($relatedItems as $related)
                <a href="{{ route('marketplace.show', $related) }}" class="flex items-center gap-3 p-2 rounded-lg hover:bg-white/5 transition mb-1 text-brand-400">
                    <div class="w-10 h-10 rounded-lg bg-brand-500/10 flex items-center justify-center">
                        <i data-lucide="package" class="w-5 h-5"></i>
                    </div>
                    <div class="flex-1 min-w-0"><p class="text-sm truncate text-gray-200">{{ $related->title }}</p></div>
                    <span class="text-brand-400 text-sm font-semibold">৳{{ number_format($related->price, 2) }}</span>
                </a>
                @endforeach
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
