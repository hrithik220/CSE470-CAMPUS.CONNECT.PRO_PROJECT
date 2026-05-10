@extends('layouts.app')
@section('title', $user->name . ' — Profile')
@section('page_title', 'Profile')

@section('content')
<div class="max-w-3xl mx-auto fade-in space-y-6">
    <div class="glass rounded-xl p-6">
        <div class="flex items-center gap-5">
            <img src="{{ $user->avatar_url }}" class="w-20 h-20 rounded-2xl ring-2 ring-brand-500/30 shadow-xl" alt="">
            <div class="flex-1">
                <h1 class="text-2xl font-bold">{{ $user->name }}</h1>
                <p class="text-gray-400 text-sm">{{ $user->email }}</p>
                @if($user->bio)<p class="text-gray-300 text-sm mt-2">{{ $user->bio }}</p>@endif
                <div class="flex gap-2 mt-3">
                    @foreach($badges as $badge)
                    @php
                        $iconMap = [
                            '🌱' => 'sprout', '🌿' => 'leaf', '🌳' => 'tree-pine', '🥉' => 'medal', '🥈' => 'award', '🥇' => 'trophy', '🔥' => 'flame', '💎' => 'gem', '⚡' => 'zap', '🛡️' => 'shield-check'
                        ];
                        $lucideIcon = $iconMap[$badge->icon] ?? 'award';
                    @endphp
                    <div title="{{ $badge->name }}" class="p-1 rounded-md bg-white/5" style="color: {{ $badge->color }}">
                        <i data-lucide="{{ $lucideIcon }}" class="w-5 h-5"></i>
                    </div>
                    @endforeach
                </div>
            </div>
            @if($user->id === auth()->id())
            <a href="{{ route('profile.edit') }}" class="px-4 py-2 bg-white/5 hover:bg-white/10 border border-white/10 rounded-lg text-sm transition">Edit Profile</a>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-4 gap-4">
        <div class="glass rounded-xl p-4 text-center"><p class="text-2xl font-bold text-brand-400">{{ $user->karma_points }}</p><p class="text-[10px] text-gray-500">Karma</p></div>
        <div class="glass rounded-xl p-4 text-center"><p class="text-2xl font-bold text-green-400">{{ $user->sales_count ?? 0 }}</p><p class="text-[10px] text-gray-500">Sales</p></div>
        <div class="glass rounded-xl p-4 text-center"><p class="text-2xl font-bold text-yellow-400">{{ number_format($user->average_rating, 1) }}</p><p class="text-[10px] text-gray-500">Rating</p></div>
        <div class="glass rounded-xl p-4 text-center"><p class="text-2xl font-bold text-blue-400">{{ $user->items_count ?? 0 }}</p><p class="text-[10px] text-gray-500">Listings</p></div>
    </div>

    <div class="glass rounded-xl p-5">
        <h3 class="font-semibold text-lg mb-4">Recent Reviews</h3>
        @forelse($recentReviews as $review)
        <div class="p-3 rounded-lg bg-white/5 mb-2">
            <div class="flex items-center gap-2 mb-1">
                <img src="{{ $review->reviewer->avatar_url }}" class="w-6 h-6 rounded-full" alt="">
                <span class="text-sm font-medium">{{ $review->reviewer->name }}</span>
                <div class="text-yellow-400 text-sm">{!! $review->stars_html !!}</div>
                <span class="text-[10px] text-gray-500 ml-auto">{{ $review->created_at->diffForHumans() }}</span>
            </div>
            <p class="text-xs text-gray-400">{{ $review->comment }}</p>
            <p class="text-[10px] text-gray-600 mt-1">For: {{ $review->item->title ?? '—' }}</p>
        </div>
        @empty
        <p class="text-gray-500 text-sm text-center py-6">No reviews yet.</p>
        @endforelse
    </div>
</div>
@endsection
