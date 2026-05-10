@extends('layouts.app')

@section('title', 'Admin Items')
@section('page_title', 'Admin Items Review')

@section('content')
<div class="glass rounded-2xl p-6">

    <h2 class="text-2xl font-bold text-white mb-6">All Marketplace Items</h2>

    <div class="space-y-4">
        @forelse($items as $item)
            <div class="p-4 rounded-xl border {{ $item->is_flagged ? 'border-red-500/40 bg-red-500/10' : 'border-white/10 bg-white/5' }}">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <h3 class="font-semibold text-white">{{ $item->title }}</h3>
                        <p class="text-sm text-gray-400">
                            Seller: {{ $item->seller->name ?? 'Unknown' }} |
                            Price: ৳{{ number_format($item->price, 2) }} |
                            Category: {{ $item->category }}
                        </p>

                        @if($item->is_flagged)
                            <p class="text-sm text-red-300 mt-2">
                                ⚠ Flagged: {{ $item->flag_reason ?? 'Suspicious activity' }}
                            </p>
                        @endif
                    </div>

                    <a href="{{ route('marketplace.show', $item) }}" class="px-4 py-2 rounded-lg bg-brand-600 text-white hover:bg-brand-500">
                        View
                    </a>
                </div>
            </div>
        @empty
            <p class="text-gray-400">No items found.</p>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $items->links() }}
    </div>

</div>
@endsection