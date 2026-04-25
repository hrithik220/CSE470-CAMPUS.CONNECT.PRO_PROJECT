{{-- resources/views/karma/index.blade.php --}}
@extends('layouts.app')

@section('title', 'My Karma — Campus Connect Pro')

@push('styles')
<style>
    .badge-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 14px;
        border-radius: 9999px;
        font-weight: 700;
        font-size: 0.78rem;
        letter-spacing: 0.04em;
        text-transform: uppercase;
    }
    .badge-Newcomer    { background:#e5e7eb; color:#374151; }
    .badge-Member      { background:#dbeafe; color:#1d4ed8; }
    .badge-Contributor { background:#d1fae5; color:#065f46; }
    .badge-Helper      { background:#fef3c7; color:#92400e; }
    .badge-Trusted     { background:#ede9fe; color:#5b21b6; }
    .badge-Champion    { background:#fce7f3; color:#9d174d; }
    .badge-Legend      { background:linear-gradient(135deg,#fbbf24,#f59e0b); color:#fff; }

    .karma-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 20px 24px;
        transition: box-shadow .2s;
    }
    .karma-card:hover { box-shadow: 0 4px 16px rgba(0,0,0,.08); }

    .progress-bar-bg { background: #f3f4f6; border-radius: 9999px; height: 8px; overflow: hidden; }
    .progress-bar-fill { height: 100%; border-radius: 9999px; background: linear-gradient(90deg,#6366f1,#8b5cf6); transition: width .6s ease; }

    .action-positive { color: #16a34a; font-weight: 700; }
    .action-negative { color: #dc2626; font-weight: 700; }
</style>
@endpush

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">⭐ Karma Dashboard</h1>
            <p class="text-sm text-gray-500 mt-1">Your reputation across Campus Connect Pro</p>
        </div>
        <a href="{{ route('karma.leaderboard') }}"
           class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white text-sm font-semibold rounded-lg hover:bg-indigo-700 transition">
            🏆 Leaderboard
        </a>
    </div>

    {{-- Stats row --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">

        {{-- Total karma --}}
        <div class="karma-card text-center">
            <div class="text-4xl font-black text-indigo-600">{{ number_format($user->karma_total) }}</div>
            <div class="text-xs text-gray-400 uppercase tracking-wider mt-1">Total Karma</div>
            <div class="mt-2">
                <span class="badge-pill badge-{{ $user->karma_badge }}">
                    {{ $user->karma_badge }}
                </span>
            </div>
        </div>

        {{-- Progress to next badge --}}
        <div class="karma-card">
            <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Next Badge</div>
            @if($nextBadge)
                <div class="font-bold text-gray-800 mb-1">{{ $nextBadge }} <span class="text-sm text-gray-400">({{ $pointsLeft }} pts away)</span></div>
                @php
                    $prevThreshold = collect(array_keys(\App\Models\KarmaPoint::BADGES))
                        ->filter(fn($t) => $t <= $user->karma_total)->last() ?? 0;
                    $nextThreshold = $prevThreshold + $pointsLeft;
                    $pct = $nextThreshold > 0
                        ? round((($user->karma_total - $prevThreshold) / ($nextThreshold - $prevThreshold)) * 100)
                        : 100;
                @endphp
                <div class="progress-bar-bg mt-2">
                    <div class="progress-bar-fill" style="width: {{ $pct }}%"></div>
                </div>
                <div class="text-xs text-gray-400 mt-1">{{ $pct }}% there</div>
            @else
                <div class="font-bold text-indigo-600">🎉 Max Badge Achieved!</div>
            @endif
        </div>

        {{-- Module breakdown --}}
        <div class="karma-card">
            <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">By Module</div>
            <ul class="space-y-1 text-sm">
                @foreach($summary as $module => $pts)
                <li class="flex justify-between">
                    <span class="capitalize text-gray-700">{{ $module }}</span>
                    <span class="{{ $pts >= 0 ? 'text-green-600' : 'text-red-500' }} font-semibold">
                        {{ $pts >= 0 ? '+' : '' }}{{ $pts }}
                    </span>
                </li>
                @endforeach
            </ul>
        </div>
    </div>

    {{-- History table --}}
    <div class="karma-card">
        <h2 class="text-base font-semibold text-gray-800 mb-4">Recent Activity</h2>

        @if($history->isEmpty())
            <p class="text-center text-gray-400 py-8">No karma activity yet. Start engaging on the platform!</p>
        @else
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-100 text-xs text-gray-400 uppercase tracking-wider">
                        <th class="pb-2 text-left">Date</th>
                        <th class="pb-2 text-left">Description</th>
                        <th class="pb-2 text-left">Module</th>
                        <th class="pb-2 text-right">Points</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($history as $entry)
                    <tr class="hover:bg-gray-50">
                        <td class="py-2 text-gray-400">{{ $entry->created_at->format('d M, H:i') }}</td>
                        <td class="py-2 text-gray-700">{{ $entry->description }}</td>
                        <td class="py-2">
                            <span class="inline-block px-2 py-0.5 bg-gray-100 text-gray-600 rounded text-xs capitalize">
                                {{ $entry->module }}
                            </span>
                        </td>
                        <td class="py-2 text-right">
                            <span class="{{ $entry->points >= 0 ? 'action-positive' : 'action-negative' }}">
                                {{ $entry->points >= 0 ? '+' : '' }}{{ $entry->points }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $history->links() }}</div>
        @endif
    </div>
</div>
@endsection
