{{-- resources/views/karma/leaderboard.blade.php --}}
@extends('layouts.app')

@section('title', 'Karma Leaderboard — Campus Connect Pro')

@section('content')
<div class="max-w-2xl mx-auto px-4 py-8">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">🏆 Karma Leaderboard</h1>
            <p class="text-sm text-gray-500 mt-1">Top contributors on campus</p>
        </div>
        <a href="{{ route('karma.index') }}"
           class="text-sm text-indigo-600 hover:underline font-medium">← My Karma</a>
    </div>

    @auth
    <div class="mb-4 px-4 py-3 bg-indigo-50 border border-indigo-100 rounded-xl text-sm text-indigo-700 font-medium">
        Your current rank: <strong>#{{ $myRank }}</strong>
    </div>
    @endauth

    <div class="space-y-2">
        @foreach($leaders as $i => $leader)
        @php
            $rank = $i + 1;
            $isMe = auth()->id() === $leader->id;
            $medal = match($rank) { 1 => '🥇', 2 => '🥈', 3 => '🥉', default => "#$rank" };
        @endphp
        <div class="flex items-center gap-4 px-5 py-4 rounded-xl border transition
                    {{ $isMe ? 'bg-indigo-50 border-indigo-300' : 'bg-white border-gray-100 hover:border-gray-200' }}">
            <div class="w-10 text-center font-black text-lg">{{ $medal }}</div>
            <div class="flex-1">
                <div class="font-semibold text-gray-800">
                    {{ $leader->name }}
                    @if($isMe) <span class="text-xs text-indigo-500 ml-1">(you)</span> @endif
                </div>
                <div class="text-xs text-gray-400">{{ $leader->karma_badge }}</div>
            </div>
            <div class="text-right">
                <div class="font-black text-indigo-600 text-lg">{{ number_format($leader->karma_total) }}</div>
                <div class="text-xs text-gray-400">karma</div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
