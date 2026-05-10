@extends('layouts.app')
@section('title', 'Karma Points — Campus Connect Pro')
@section('page_title', 'Karma Points')

@section('content')
<div class="fade-in space-y-6">
    {{-- Karma Overview --}}
    <div class="glass rounded-xl p-6 text-center">
        <p class="text-6xl font-extrabold gradient-text mb-2">{{ $user->karma_points }}</p>
        <p class="text-gray-400">Total Karma Points</p>
        <div class="flex justify-center gap-4 mt-4">
            <a href="{{ route('karma.leaderboard') }}" class="px-5 py-2 bg-brand-500/10 hover:bg-brand-500/20 text-brand-400 rounded-lg text-sm transition flex items-center gap-2">
                <i data-lucide="award" class="w-4 h-4"></i> Leaderboard
            </a>
            <a href="{{ route('karma.sustainability') }}" class="px-5 py-2 bg-green-500/10 hover:bg-green-500/20 text-green-400 rounded-lg text-sm transition flex items-center gap-2">
                <i data-lucide="leaf" class="w-4 h-4"></i> Sustainability
            </a>
        </div>
    </div>

    <div class="grid lg:grid-cols-2 gap-6">
        {{-- Badges --}}
        <div class="glass rounded-xl p-5">
            <h3 class="font-semibold text-lg mb-4">🏅 My Badges</h3>
            @forelse($badges as $badge)
            <div class="flex items-center gap-3 p-3 rounded-lg bg-white/5 mb-2">
                @php
                    $iconMap = [
                        '🌱' => 'sprout', '🌿' => 'leaf', '🌳' => 'tree-pine', '🥉' => 'medal', '🥈' => 'award', '🥇' => 'trophy', '🔥' => 'flame', '💎' => 'gem', '⚡' => 'zap', '🛡️' => 'shield-check'
                    ];
                    $lucideIcon = $iconMap[$badge->icon] ?? 'award';
                @endphp
                <div class="w-12 h-12 rounded-xl flex items-center justify-center bg-white/5" style="color: {{ $badge->color }}">
                    <i data-lucide="{{ $lucideIcon }}" class="w-7 h-7"></i>
                </div>
                <div>
                    <p class="font-semibold text-sm" style="color: {{ $badge->color }}">{{ $badge->name }}</p>
                    <p class="text-xs text-gray-500">{{ $badge->description }}</p>
                    <p class="text-[10px] text-gray-600 mt-0.5">Earned {{ $badge->pivot->earned_at ? \Carbon\Carbon::parse($badge->pivot->earned_at)->diffForHumans() : '' }}</p>
                </div>
            </div>
            @empty
            <p class="text-gray-500 text-sm text-center py-6">No badges earned yet. Keep trading to earn badges!</p>
            @endforelse
        </div>

        {{-- Karma Log --}}
        <div class="glass rounded-xl p-5">
            <h3 class="font-semibold text-lg mb-4">📊 Karma History</h3>
            <div class="space-y-2 max-h-96 overflow-y-auto">
                @forelse($recentLogs as $log)
                <div class="flex items-center gap-3 p-3 rounded-lg bg-white/5">
                    <div class="w-10 h-10 rounded-lg {{ $log->is_positive ? 'bg-green-500/10' : 'bg-red-500/10' }} flex items-center justify-center text-sm font-bold {{ $log->is_positive ? 'text-green-400' : 'text-red-400' }}">
                        {{ $log->is_positive ? '+' : '' }}{{ $log->points }}
                    </div>
                    <div class="flex-1">
                        <p class="text-sm">{{ $log->description }}</p>
                        <p class="text-[10px] text-gray-500">{{ $log->created_at->diffForHumans() }}</p>
                    </div>
                </div>
                @empty
                <p class="text-gray-500 text-sm text-center py-6">No karma activity yet.</p>
                @endforelse
            </div>
        </div>
    </div>

    {{-- How Karma Works --}}
    <div class="glass rounded-xl p-5">
        <h3 class="font-semibold text-lg mb-4">💡 How Karma Works</h3>
        <div class="grid sm:grid-cols-3 gap-4">
            <div class="p-4 rounded-lg bg-green-500/5 border border-green-500/10 text-center">
                <i data-lucide="shopping-cart" class="w-8 h-8 mx-auto mb-2 text-green-400"></i>
                <p class="text-sm font-semibold text-green-400">+10 per sale</p>
                <p class="text-xs text-gray-500 mt-1">Complete a marketplace transaction</p>
            </div>
            <div class="p-4 rounded-lg bg-yellow-500/5 border border-yellow-500/10 text-center">
                <i data-lucide="star" class="w-8 h-8 mx-auto mb-2 text-yellow-400"></i>
                <p class="text-sm font-semibold text-yellow-400">+1 to +5 per review</p>
                <p class="text-xs text-gray-500 mt-1">Based on star rating received</p>
            </div>
            <div class="p-4 rounded-lg bg-purple-500/5 border border-purple-500/10 text-center">
                <i data-lucide="award" class="w-8 h-8 mx-auto mb-2 text-purple-400"></i>
                <p class="text-sm font-semibold text-purple-400">Badges unlock</p>
                <p class="text-xs text-gray-500 mt-1">At 25, 50, 100, 150, 200 karma</p>
            </div>
        </div>
    </div>
</div>
@endsection
