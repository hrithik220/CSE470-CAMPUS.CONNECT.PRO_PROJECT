@extends('layouts.app')
@section('title', 'Marketplace — Campus Connect Pro')
@section('page_title', 'Marketplace')

@section('content')
<div class="fade-in space-y-6">
    {{-- Search & Filters --}}
    <form method="GET" action="{{ route('marketplace.index') }}" class="glass rounded-xl p-5">
        <div class="grid md:grid-cols-6 gap-3">
            <div class="md:col-span-2">
                <input type="text" name="keyword" value="{{ request('keyword') }}" placeholder="Search items..."
                    class="w-full px-4 py-2.5 rounded-lg bg-white/5 border border-white/10 text-white placeholder-gray-500 focus:border-brand-500 outline-none transition text-sm">
            </div>
            <select name="category" class="px-3 py-2.5 rounded-lg bg-white/5 border border-white/10 text-gray-300 text-sm focus:border-brand-500 outline-none">
                <option value="">All Categories</option>
                @foreach($categories as $key => $label)
                <option value="{{ $key }}" {{ request('category') === $key ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
            <select name="condition" class="px-3 py-2.5 rounded-lg bg-white/5 border border-white/10 text-gray-300 text-sm focus:border-brand-500 outline-none">
                <option value="">Any Condition</option>
                @foreach($conditions as $key => $label)
                <option value="{{ $key }}" {{ request('condition') === $key ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
            <select name="sort" class="px-3 py-2.5 rounded-lg bg-white/5 border border-white/10 text-gray-300 text-sm focus:border-brand-500 outline-none">
                <option value="latest" {{ request('sort') === 'latest' ? 'selected' : '' }}>Newest First</option>
                <option value="price_low" {{ request('sort') === 'price_low' ? 'selected' : '' }}>Price: Low → High</option>
                <option value="price_high" {{ request('sort') === 'price_high' ? 'selected' : '' }}>Price: High → Low</option>
                <option value="popular" {{ request('sort') === 'popular' ? 'selected' : '' }}>Most Popular</option>
            </select>
            <button type="submit" class="px-6 py-2.5 bg-brand-600 hover:bg-brand-500 text-white font-medium rounded-lg transition text-sm">Search</button>
        </div>
    </form>

    {{-- Items Grid --}}
    <div class="grid sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
        @forelse($items as $item)
        <a href="{{ route('marketplace.show', $item) }}" class="glass rounded-xl overflow-hidden card-hover group" id="item-{{ $item->id }}">
            <div class="aspect-[4/3] bg-surface-800 relative overflow-hidden">
                @if($item->images->count())
                <img src="{{ asset('storage/' . $item->images->first()->image_path) }}" alt="{{ $item->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                @else
                <div class="w-full h-full flex items-center justify-center text-brand-400">
                    <i data-lucide="{{ ['textbooks'=>'book','electronics'=>'laptop','furniture'=>'armchair','clothing'=>'shirt','sports'=>'trophy','supplies'=>'pencil','tickets'=>'ticket','other'=>'package'][$item->category] ?? 'package' }}" class="w-12 h-12 opacity-30"></i>
                </div>
                @endif
                <span class="absolute top-3 right-3 px-2 py-1 text-[10px] font-bold uppercase rounded-md {{ $item->condition_badge_color }} text-white">{{ $item->condition_label }}</span>
            </div>
            <div class="p-4">
                <h3 class="font-semibold text-sm truncate group-hover:text-brand-400 transition">{{ $item->title }}</h3>
                <p class="text-xs text-gray-500 mt-1">{{ $item->category_label }}</p>
                <div class="flex items-center justify-between mt-3">
                    <span class="text-lg font-bold text-brand-400">৳{{ number_format($item->price, 2) }}</span>
                    <div class="flex items-center gap-1.5">
                        <img src="{{ $item->seller->avatar_url }}" class="w-5 h-5 rounded-full" alt="">
                        <span class="text-xs text-gray-500">{{ $item->seller->name }}</span>
                    </div>
                </div>
            </div>
        </a>
        @empty
        <div class="col-span-full text-center py-16">
            <div class="w-16 h-16 bg-white/5 rounded-2xl flex items-center justify-center mx-auto mb-4 text-gray-500">
                <i data-lucide="search-x" class="w-8 h-8"></i>
            </div>
            <p class="text-gray-400 font-medium">No items found</p>
            <p class="text-gray-500 text-sm mt-1">Try adjusting your filters or <a href="{{ route('marketplace.create') }}" class="text-brand-400">list something!</a></p>
        </div>
        @endforelse
    </div>

    {{ $items->links() }}
</div>
@endsection
