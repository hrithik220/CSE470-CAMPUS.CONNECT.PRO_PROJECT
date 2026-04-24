@extends('layouts.app')
@section('title', $item->title)

@section('content')
<div class="max-w-4xl mx-auto">
    <a href="{{ route('marketplace.index') }}" class="text-blue-600 hover:underline text-sm mb-4 inline-block">
        ← Back to Marketplace
    </a>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            {{-- Photos --}}
            <div>
                @if($item->photos && count($item->photos) > 0)
                    <img src="{{ Storage::url($item->photos[0]) }}"
                         alt="{{ $item->title }}"
                         class="w-full rounded-lg object-cover max-h-72">
                @else
                    <div class="w-full h-60 bg-gray-200 rounded-lg flex items-center justify-center text-gray-400">
                        No Photo Available
                    </div>
                @endif
            </div>

            {{-- Item Details --}}
            <div class="space-y-3">
                <h1 class="text-2xl font-bold text-gray-800">{{ $item->title }}</h1>
                <p class="text-3xl font-bold text-blue-600">৳{{ number_format($item->price, 2) }}</p>

                <div class="flex gap-2 flex-wrap">
                    <span class="bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-sm capitalize">
                        {{ str_replace('_', ' ', $item->condition_rating) }}
                    </span>
                    <span class="bg-blue-50 text-blue-700 px-3 py-1 rounded-full text-sm capitalize">
                        {{ $item->category }}
                    </span>
                    <span class="px-3 py-1 rounded-full text-sm font-medium
                        {{ $item->status == 'available' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                        {{ ucfirst($item->status) }}
                    </span>
                </div>

                @if($item->description)
                    <p class="text-gray-600 text-sm leading-relaxed">{{ $item->description }}</p>
                @endif

                {{-- Seller Info with Credibility (F3) --}}
                <div class="border rounded-lg p-3 bg-gray-50">
                    <p class="text-sm text-gray-500 mb-1">Sold by</p>
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="font-semibold text-gray-800">{{ $item->seller->name }}</p>
                            <div class="flex items-center gap-1">
                                @for($i = 1; $i <= 5; $i++)
                                    <span class="text-sm {{ $i <= round(($item->seller->credibility_score / 10) * 5) ? 'text-yellow-400' : 'text-gray-300' }}">★</span>
                                @endfor
                                <span class="text-xs text-gray-500 ml-1">
                                    Credibility: {{ number_format($item->seller->credibility_score, 1) }}/10
                                </span>
                            </div>
                        </div>
                        <a href="{{ route('marketplace.seller.profile', $item->seller_id) }}"
                           class="text-blue-600 text-sm hover:underline">
                            View Profile →
                        </a>
                    </div>
                </div>

                {{-- Action Buttons --}}
                @auth
                    @if(Auth::id() !== $item->seller_id && $item->status === 'available')
                        <a href="{{ route('marketplace.chat', $item) }}"
                           class="block w-full text-center bg-blue-600 text-white py-3 rounded-lg font-semibold hover:bg-blue-700 transition">
                            💬 Chat with Seller
                        </a>
                        <form method="POST" action="{{ route('marketplace.buy', $item) }}">
                            @csrf
                            <button type="submit"
                                    class="w-full bg-green-600 text-white py-3 rounded-lg font-semibold hover:bg-green-700 transition">
                                🛒 Request to Buy
                            </button>
                        </form>
                    @endif

                    @if(Auth::id() === $item->seller_id)
                        <div class="flex gap-2 mt-2">
                            <a href="{{ route('marketplace.edit', $item) }}"
                               class="flex-1 text-center bg-yellow-500 text-white py-2 rounded hover:bg-yellow-600">
                                Edit
                            </a>
                            <form method="POST" action="{{ route('marketplace.destroy', $item) }}"
                                  onsubmit="return confirm('Delete this item?')">
                                @csrf @method('DELETE')
                                <button class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">
                                    Delete
                                </button>
                            </form>
                        </div>
                    @endif
                @endauth
            </div>
        </div>
    </div>
</div>
@endsection
