@extends('layouts.app')

@section('title', 'Admin Dashboard')
@section('page_title', 'Admin Dashboard')

@section('content')
<div class="space-y-6">

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="glass rounded-2xl p-6">
            <p class="text-gray-400 text-sm">Total Users</p>
            <h2 class="text-4xl font-bold text-white mt-2">{{ $totalUsers }}</h2>
        </div>

        <div class="glass rounded-2xl p-6">
            <p class="text-gray-400 text-sm">Total Items</p>
            <h2 class="text-4xl font-bold text-white mt-2">{{ $totalItems }}</h2>
        </div>

        <div class="glass rounded-2xl p-6">
            <p class="text-gray-400 text-sm">Flagged Items</p>
            <h2 class="text-4xl font-bold text-red-400 mt-2">{{ $flaggedItems }}</h2>
        </div>
    </div>

    <div class="glass rounded-2xl p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-xl font-bold text-white">Recent Flagged Items</h3>

            <a href="{{ route('admin.items') }}" class="text-brand-400 hover:text-brand-300">
                View All
            </a>
        </div>

        @forelse($recentFlaggedItems as $item)
            <div class="p-4 rounded-xl bg-red-500/10 border border-red-500/20 mb-3">
                <h4 class="font-semibold text-white">{{ $item->title }}</h4>
                <p class="text-sm text-gray-400">Seller: {{ $item->seller->name ?? 'Unknown' }}</p>
                <p class="text-sm text-red-300">Reason: {{ $item->flag_reason ?? 'Suspicious activity' }}</p>
            </div>
        @empty
            <p class="text-gray-400">No flagged items found.</p>
        @endforelse
    </div>

</div>
@endsection