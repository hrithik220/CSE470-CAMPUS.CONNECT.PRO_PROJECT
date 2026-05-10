@extends('layouts.app')

@section('title', 'Leaderboard')
@section('page_title', 'Monthly Leaderboard & Badges')

@section('content')
@php
    $users = \App\Models\User::orderByDesc('karma_points')->take(10)->get();

    $topUser = $users->first();

    $totalKarma = \App\Models\User::sum('karma_points');

    $totalUsers = \App\Models\User::count();

    $badgeFor = function ($user, $rank) {
        if ($rank === 1) {
            return ['Top Contributor', 'award', 'text-yellow-300 bg-yellow-500/10 border-yellow-500/20'];
        }

        if (($user->karma_points ?? 0) >= 100) {
            return ['Campus Hero', 'shield-check', 'text-purple-300 bg-purple-500/10 border-purple-500/20'];
        }

        if (($user->karma_points ?? 0) >= 50) {
            return ['Top Tutor', 'graduation-cap', 'text-blue-300 bg-blue-500/10 border-blue-500/20'];
        }

        if (($user->karma_points ?? 0) >= 25) {
            return ['Ride Hero', 'car', 'text-green-300 bg-green-500/10 border-green-500/20'];
        }

        if (($user->karma_points ?? 0) >= 10) {
            return ['Best Seller', 'shopping-bag', 'text-pink-300 bg-pink-500/10 border-pink-500/20'];
        }

        return ['New Member', 'sparkles', 'text-gray-300 bg-white/5 border-white/10'];
    };
@endphp

<div class="space-y-6">

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="glass rounded-2xl p-6">
            <p class="text-gray-400 text-sm">Total Karma Earned</p>
            <h2 class="text-4xl font-bold text-yellow-300 mt-2">⚡ {{ $totalKarma }}</h2>
            <p class="text-xs text-gray-500 mt-2">Across all positive student actions</p>
        </div>

        <div class="glass rounded-2xl p-6">
            <p class="text-gray-400 text-sm">Active Contributors</p>
            <h2 class="text-4xl font-bold text-indigo-300 mt-2">{{ $totalUsers }}</h2>
            <p class="text-xs text-gray-500 mt-2">Students ranked by karma points</p>
        </div>

        <div class="glass rounded-2xl p-6">
            <p class="text-gray-400 text-sm">Current Champion</p>
            <h2 class="text-2xl font-bold text-green-300 mt-2">
                {{ $topUser?->name ?? 'No user yet' }}
            </h2>
            <p class="text-xs text-gray-500 mt-2">
                {{ $topUser ? ($topUser->karma_points . ' karma points') : 'No karma activity yet' }}
            </p>
        </div>
    </div>

    <div class="glass rounded-2xl p-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
            <div>
                <h2 class="text-2xl font-bold text-white">Top Contributors</h2>
                <p class="text-gray-400 text-sm mt-1">
                    Monthly leaderboard based on marketplace, ride sharing, tutoring, and review activity.
                </p>
            </div>

            <div class="px-4 py-2 rounded-xl bg-indigo-500/10 border border-indigo-500/20 text-indigo-300 text-sm">
                Badge system: Top Tutor • Ride Hero • Best Seller
            </div>
        </div>

        <div class="space-y-4">
            @forelse($users as $index => $user)
                @php
                    $rank = $index + 1;
                    [$badge, $icon, $badgeClass] = $badgeFor($user, $rank);

                    $rankClass = match($rank) {
                        1 => 'bg-yellow-500/20 text-yellow-300 border-yellow-500/30',
                        2 => 'bg-gray-400/20 text-gray-200 border-gray-400/30',
                        3 => 'bg-orange-500/20 text-orange-300 border-orange-500/30',
                        default => 'bg-indigo-500/10 text-indigo-300 border-indigo-500/20',
                    };
                @endphp

                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 p-5 rounded-2xl bg-white/5 border border-white/10 hover:bg-white/10 transition">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl border {{ $rankClass }} flex items-center justify-center font-bold text-xl">
                            #{{ $rank }}
                        </div>

                        <div class="w-12 h-12 rounded-full bg-indigo-600 flex items-center justify-center font-bold">
                            {{ strtoupper(substr($user->name ?? 'U', 0, 2)) }}
                        </div>

                        <div>
                            <h3 class="text-lg font-bold text-white">{{ $user->name }}</h3>
                            <p class="text-sm text-gray-400">{{ $user->email }}</p>
                        </div>
                    </div>

                    <div class="flex flex-col md:items-end gap-2">
                        <div class="px-4 py-2 rounded-xl border {{ $badgeClass }} flex items-center gap-2">
                            <i data-lucide="{{ $icon }}" class="w-4 h-4"></i>
                            <span class="font-semibold">{{ $badge }}</span>
                        </div>

                        <div class="text-yellow-300 font-bold text-xl">
                            ⚡ {{ $user->karma_points ?? 0 }} karma
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-gray-400">No leaderboard data yet.</p>
            @endforelse
        </div>
    </div>

    <div class="glass rounded-2xl p-6">
        <h2 class="text-xl font-bold text-white mb-4">How Karma Points Are Awarded</h2>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="p-4 rounded-xl bg-green-500/10 border border-green-500/20">
                <i data-lucide="car" class="w-6 h-6 text-green-300 mb-2"></i>
                <h3 class="font-semibold text-white">Ride Sharing</h3>
                <p class="text-sm text-gray-400 mt-1">Earn karma for sharing rides and helping students commute.</p>
            </div>

            <div class="p-4 rounded-xl bg-blue-500/10 border border-blue-500/20">
                <i data-lucide="shopping-bag" class="w-6 h-6 text-blue-300 mb-2"></i>
                <h3 class="font-semibold text-white">Marketplace</h3>
                <p class="text-sm text-gray-400 mt-1">Earn karma after successful item transactions.</p>
            </div>

            <div class="p-4 rounded-xl bg-purple-500/10 border border-purple-500/20">
                <i data-lucide="graduation-cap" class="w-6 h-6 text-purple-300 mb-2"></i>
                <h3 class="font-semibold text-white">Tutoring</h3>
                <p class="text-sm text-gray-400 mt-1">Earn karma for completing tutoring sessions.</p>
            </div>

            <div class="p-4 rounded-xl bg-yellow-500/10 border border-yellow-500/20">
                <i data-lucide="star" class="w-6 h-6 text-yellow-300 mb-2"></i>
                <h3 class="font-semibold text-white">Positive Reviews</h3>
                <p class="text-sm text-gray-400 mt-1">Earn karma when other students rate you positively.</p>
            </div>
        </div>
    </div>
</div>
@endsection