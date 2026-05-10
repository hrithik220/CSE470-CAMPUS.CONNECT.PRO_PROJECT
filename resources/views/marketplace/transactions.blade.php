@extends('layouts.app')
@section('title', 'Transaction History')
@section('page_title', 'Transaction History')

@section('content')
<div class="max-w-3xl mx-auto fade-in space-y-2">
    @forelse($transactions as $tx)
    <a href="{{ route('transactions.show', $tx) }}" class="glass rounded-xl p-4 flex items-center gap-4 card-hover block">
        <div class="w-12 h-12 rounded-xl {{ $tx->status === 'completed' ? 'bg-green-500/10 text-green-400' : ($tx->status === 'cancelled' ? 'bg-red-500/10 text-red-400' : 'bg-yellow-500/10 text-yellow-400') }} flex items-center justify-center">
            <i data-lucide="{{ $tx->status === 'completed' ? 'check-circle' : ($tx->status === 'cancelled' ? 'x-circle' : 'clock') }}" class="w-6 h-6"></i>
        </div>
        <div class="flex-1 min-w-0">
            <p class="text-sm font-semibold truncate">{{ $tx->item->title ?? 'Deleted Item' }}</p>
            <p class="text-xs text-gray-500">{{ $tx->buyer_id === auth()->id() ? 'Bought from ' . $tx->seller->name : 'Sold to ' . $tx->buyer->name }} · {{ $tx->created_at->diffForHumans() }}</p>
        </div>
        <div class="text-right">
            <p class="font-bold {{ $tx->buyer_id === auth()->id() ? 'text-red-400' : 'text-green-400' }}">৳{{ number_format($tx->amount, 2) }}</p>
            <p class="text-[10px] text-gray-500 uppercase">{{ $tx->status }}</p>
        </div>
    </a>
    @empty
    <div class="text-center py-16">
        <div class="w-16 h-16 bg-white/5 rounded-2xl flex items-center justify-center mx-auto mb-4 text-gray-500">
            <i data-lucide="clipboard-list" class="w-8 h-8"></i>
        </div>
        <p class="text-gray-400">No transactions yet.</p>
    </div>
    @endforelse
    {{ $transactions->links() }}
</div>
@endsection
