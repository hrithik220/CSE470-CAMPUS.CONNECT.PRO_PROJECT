@extends('layouts.app')
@section('title', 'Marketplace')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Campus Marketplace</h1>
    <a href="{{ route('marketplace.create') }}"
       class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
        + List an Item
    </a>
</div>

{{-- Search & Filter Form --}}
<form method="GET" action="{{ route('marketplace.index') }}"
      class="bg-white rounded-lg shadow p-4 mb-6 flex flex-wrap gap-3">
    <input type="text" name="search" value="{{ request('search') }}"
           placeholder="Search items..."
           class="border rounded px-3 py-2 flex-1 min-w-[180px]">

    <select name="category" class="border rounded px-3 py-2">
        <option value="">All Categories</option>
        <option value="books"       {{ request('category')=='books' ? 'selected' : '' }}>Books</option>
        <option value="electronics" {{ request('category')=='electronics' ? 'selected' : '' }}>Electronics</option>
        <option value="clothing"    {{ request('category')=='clothing' ? 'selected' : '' }}>Clothing</option>
        <option value="furniture"   {{ request('category')=='furniture' ? 'selected' : '' }}>Furniture</option>
        <option value="other"       {{ request('category')=='other' ? 'selected' : '' }}>Other</option>
    </select>

    <select name="condition" class="border rounded px-3 py-2">
        <option value="">Any Condition</option>
        <option value="new"      {{ request('condition')=='new' ? 'selected' : '' }}>New</option>
        <option value="like_new" {{ request('condition')=='like_new' ? 'selected' : '' }}>Like New</option>
        <option value="good"     {{ request('condition')=='good' ? 'selected' : '' }}>Good</option>
        <option value="fair"     {{ request('condition')=='fair' ? 'selected' : '' }}>Fair</option>
        <option value="poor"     {{ request('condition')=='poor' ? 'selected' : '' }}>Poor</option>
    </select>

    <input type="number" name="max_price" value="{{ request('max_price') }}"
           placeholder="Max Price (৳)"
           class="border rounded px-3 py-2 w-36">

    <button type="submit"
            class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
        Search
    </button>
    <a href="{{ route('marketplace.index') }}"
       class="text-gray-500 px-3 py-2 hover:underline">
        Clear
    </a>
</form>

{{-- Items Grid --}}
@if($items->isEmpty())
    <p class="text-gray-500 text-center py-12">No items found.</p>
@else
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5">
        @foreach($items as $item)
        <a href="{{ route('marketplace.show', $item) }}"
           class="bg-white rounded-lg shadow hover:shadow-md transition block overflow-hidden">
            {{-- Item Photo --}}
            @if($item->photos && count($item->photos) > 0)
                <img src="{{ Storage::url($item->photos[0]) }}"
                     alt="{{ $item->title }}"
                     class="w-full h-44 object-cover">
            @else
                <div class="w-full h-44 bg-gray-200 flex items-center justify-center text-gray-400 text-sm">
                    No Photo
                </div>
            @endif

            <div class="p-4">
                <h3 class="font-semibold text-gray-800 truncate">{{ $item->title }}</h3>
                <p class="text-blue-600 font-bold mt-1">৳{{ number_format($item->price, 2) }}</p>
                <div class="flex justify-between items-center mt-2 text-xs text-gray-500">
                    <span class="capitalize bg-gray-100 px-2 py-0.5 rounded">
                        {{ str_replace('_', ' ', $item->condition_rating) }}
                    </span>
                    <span>{{ $item->category }}</span>
                </div>
                <p class="text-xs text-gray-400 mt-2">By {{ $item->seller->name }}</p>
            </div>
        </a>
        @endforeach
    </div>

    <div class="mt-6">{{ $items->links() }}</div>
@endif
@endsection
