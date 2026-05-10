@extends('layouts.app')
@section('title', 'Transaction — Campus Connect Pro')
@section('page_title', 'Transaction Details')

@section('content')
<div class="max-w-2xl mx-auto fade-in space-y-4">
    <div class="glass rounded-xl p-6">
        <div class="flex items-center gap-4 mb-6">
            <div class="w-14 h-14 rounded-xl {{ $transaction->status === 'completed' ? 'bg-green-500/10 text-green-400' : ($transaction->status === 'cancelled' ? 'bg-red-500/10 text-red-400' : 'bg-yellow-500/10 text-yellow-400') }} flex items-center justify-center">
                <i data-lucide="{{ $transaction->status === 'completed' ? 'check-circle' : ($transaction->status === 'cancelled' ? 'x-circle' : 'clock') }}" class="w-7 h-7"></i>
            </div>
            <div>
                <h2 class="text-xl font-bold">{{ $transaction->item->title ?? 'Item' }}</h2>
                <p class="text-sm text-gray-400">Transaction #{{ $transaction->id }} · {{ ucfirst($transaction->status) }}</p>
            </div>
        </div>
        <div class="grid grid-cols-2 gap-4 mb-6">
            <div class="p-3 rounded-lg bg-white/5">
                <p class="text-xs text-gray-500">Amount</p>
                <p class="text-2xl font-bold text-brand-400">৳{{ number_format($transaction->amount, 2) }}</p>
            </div>
            <div class="p-3 rounded-lg bg-white/5">
                <p class="text-xs text-gray-500">Date</p>
                <p class="text-sm font-medium mt-1">{{ $transaction->created_at->format('M d, Y h:i A') }}</p>
            </div>
        </div>
        <div class="grid grid-cols-2 gap-4 mb-6">
            <div class="p-3 rounded-lg bg-white/5">
                <p class="text-xs text-gray-500 mb-2">Buyer</p>
                <div class="flex items-center gap-2">
                    <img src="{{ $transaction->buyer->avatar_url }}" class="w-8 h-8 rounded-full">
                    <span class="text-sm font-medium">{{ $transaction->buyer->name }}</span>
                </div>
            </div>
            <div class="p-3 rounded-lg bg-white/5">
                <p class="text-xs text-gray-500 mb-2">Seller</p>
                <div class="flex items-center gap-2">
                    <img src="{{ $transaction->seller->avatar_url }}" class="w-8 h-8 rounded-full">
                    <span class="text-sm font-medium">{{ $transaction->seller->name }}</span>
                </div>
            </div>
        </div>
        {{-- Actions --}}
        @if($transaction->status === 'pending')
        <div class="flex gap-3">
            @if($transaction->seller_id === auth()->id())
            <form method="POST" action="{{ route('transactions.complete', $transaction) }}" class="flex-1">
                @csrf
                <button class="w-full py-3 bg-green-600 hover:bg-green-500 text-white font-semibold rounded-xl transition flex items-center justify-center gap-2">
                    <i data-lucide="check" class="w-5 h-5"></i> Confirm Sale
                </button>
            </form>
            @endif
            <form method="POST" action="{{ route('transactions.cancel', $transaction) }}" class="flex-1">
                @csrf
                <button class="w-full py-3 bg-red-500/10 hover:bg-red-500/20 border border-red-500/20 text-red-400 font-medium rounded-xl transition">Cancel</button>
            </form>
        </div>
        @endif
        @if($transaction->status === 'completed' && $transaction->buyer_id === auth()->id() && !$transaction->review)
        <a href="{{ route('review.create', $transaction) }}" class="block w-full py-3 bg-brand-600 hover:bg-brand-500 text-white font-semibold rounded-xl transition text-center mt-4 flex items-center justify-center gap-2">
            <i data-lucide="star" class="w-5 h-5"></i> Leave a Review
        </a>
        @endif
        @if($transaction->review)
        <div class="mt-4 p-4 rounded-lg bg-white/5">
            <p class="text-xs text-gray-500 mb-1">Review</p>
            <div class="text-yellow-400 text-sm">{!! $transaction->review->stars_html !!}</div>
            <p class="text-sm text-gray-300 mt-1">{{ $transaction->review->comment }}</p>
        </div>
        @endif
    </div>
</div>
@endsection
