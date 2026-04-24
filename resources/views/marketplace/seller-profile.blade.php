@extends('layouts.app')
@section('title', $seller->name . "'s Profile")

@section('content')
<div class="max-w-4xl mx-auto">
    <a href="{{ route('marketplace.index') }}" class="text-blue-600 hover:underline text-sm mb-4 inline-block">
        ← Back to Marketplace
    </a>

    {{-- Seller Info Card --}}
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <div class="flex items-center gap-6">
            <div class="w-20 h-20 rounded-full bg-blue-100 flex items-center justify-center text-3xl font-bold text-blue-600">
                {{ strtoupper(substr($seller->name, 0, 1)) }}
            </div>
            <div class="flex-1">
                <h1 class="text-2xl font-bold text-gray-800">{{ $seller->name }}</h1>
                <p class="text-gray-500 text-sm">{{ $seller->university_email }}</p>

                {{-- Credibility Score --}}
                <div class="flex items-center gap-4 mt-3">
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg px-4 py-2 text-center">
                        <p class="text-2xl font-bold text-yellow-600">{{ number_format($seller->credibility_score, 1) }}/10</p>
                        <p class="text-xs text-yellow-500">Credibility Score</p>
                    </div>
                    <div class="bg-green-50 border border-green-200 rounded-lg px-4 py-2 text-center">
                        <p class="text-2xl font-bold text-green-600">{{ $totalSales }}</p>
                        <p class="text-xs text-green-500">Total Sales</p>
                    </div>
                    <div class="bg-blue-50 border border-blue-200 rounded-lg px-4 py-2 text-center">
                        <p class="text-2xl font-bold text-blue-600">{{ $totalReviews }}</p>
                        <p class="text-xs text-blue-500">Reviews</p>
                    </div>
                    <div class="bg-purple-50 border border-purple-200 rounded-lg px-4 py-2 text-center">
                        <p class="text-2xl font-bold text-purple-600">{{ $activeListings }}</p>
                        <p class="text-xs text-purple-500">Active Listings</p>
                    </div>
                </div>

                {{-- Star Rating --}}
                @if($avgRating)
                <div class="flex items-center gap-2 mt-3">
                    @for($i = 1; $i <= 5; $i++)
                        <span class="text-2xl {{ $i <= round($avgRating) ? 'text-yellow-400' : 'text-gray-300' }}">★</span>
                    @endfor
                    <span class="text-gray-600 text-sm">({{ number_format($avgRating, 1) }} avg from {{ $totalReviews }} reviews)</span>
                </div>
                @else
                    <p class="text-gray-400 text-sm mt-2">No reviews yet</p>
                @endif
            </div>
        </div>
    </div>

    {{-- Transaction History --}}
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-lg font-bold text-gray-800 mb-4">Transaction History</h2>

        @if($transactions->isEmpty())
            <p class="text-gray-400 text-center py-6">No completed transactions yet.</p>
        @else
            <div class="space-y-3">
                @foreach($transactions as $transaction)
                <div class="border rounded-lg p-4 flex justify-between items-center">
                    <div>
                        <p class="font-semibold text-gray-800">{{ $transaction->item->title }}</p>
                        <p class="text-sm text-gray-500">
                            Sold to {{ $transaction->buyer->name }} ·
                            {{ $transaction->updated_at->format('d M Y') }}
                        </p>
                        @if($transaction->review)
                            <div class="flex items-center gap-1 mt-1">
                                @for($i = 1; $i <= 5; $i++)
                                    <span class="text-sm {{ $i <= $transaction->review->rating ? 'text-yellow-400' : 'text-gray-300' }}">★</span>
                                @endfor
                                @if($transaction->review->comment)
                                    <span class="text-xs text-gray-500 ml-1">"{{ $transaction->review->comment }}"</span>
                                @endif
                            </div>
                        @endif
                    </div>
                    <div class="text-right">
                        <p class="font-bold text-blue-600">৳{{ number_format($transaction->item->price, 2) }}</p>
                        <span class="text-xs bg-green-100 text-green-700 px-2 py-1 rounded-full">Completed</span>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="mt-4">{{ $transactions->links() }}</div>
        @endif
    </div>
</div>
@endsection
