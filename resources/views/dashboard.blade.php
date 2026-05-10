@extends('layouts.app')
@section('title', 'Dashboard — Campus Connect Pro')
@section('page_title', 'Dashboard')

@section('content')
<div class="fade-in space-y-6">
    {{-- Stats Grid --}}
    <div class="grid grid-cols-2 lg:grid-cols-5 gap-4">
        @foreach([
            ['Active Listings', $stats['active_listings'], 'package', 'from-blue-500/10 to-cyan-500/10', 'border-blue-500/20', 'text-blue-400'],
            ['Total Sales', $stats['total_sales'], 'circle-dollar-sign', 'from-green-500/10 to-emerald-500/10', 'border-green-500/20', 'text-green-400'],
            ['Purchases', $stats['total_purchases'], 'shopping-cart', 'from-purple-500/10 to-violet-500/10', 'border-purple-500/20', 'text-purple-400'],
            ['Karma Points', $stats['karma_points'], 'zap', 'from-amber-500/10 to-orange-500/10', 'border-amber-500/20', 'text-amber-400'],
            ['Unread Messages', $stats['unread_messages'], 'message-square', 'from-pink-500/10 to-rose-500/10', 'border-pink-500/20', 'text-pink-400'],
        ] as $stat)
        <div class="glass rounded-xl p-4 card-hover">
            <div class="flex items-center justify-between mb-2">
                <i data-lucide="{{ $stat[2] }}" class="w-6 h-6 {{ $stat[5] }}"></i>
                <div class="w-8 h-8 rounded-lg bg-gradient-to-br {{ $stat[3] }} border {{ $stat[4] }}"></div>
            </div>
            <p class="text-2xl font-bold">{{ $stat[1] }}</p>
            <p class="text-xs text-gray-500 mt-1">{{ $stat[0] }}</p>
        </div>
        @endforeach
    </div>

    <div class="grid lg:grid-cols-2 gap-6">
        {{-- My Listings --}}
        <div class="glass rounded-xl p-5">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-semibold text-lg">My Listings</h3>
                <a href="{{ route('marketplace.create') }}" class="text-xs text-brand-400 hover:text-brand-300 transition">+ New Listing</a>
            </div>
            @forelse($myListings as $item)
            <a href="{{ route('marketplace.show', $item) }}" class="flex items-center gap-3 p-3 rounded-lg hover:bg-white/5 transition mb-1">
                <div class="w-10 h-10 rounded-lg bg-brand-500/10 flex items-center justify-center text-brand-400 flex-shrink-0">
                    <i data-lucide="{{ ['textbooks'=>'book','electronics'=>'laptop','furniture'=>'armchair','clothing'=>'shirt','sports'=>'trophy','supplies'=>'pencil','tickets'=>'ticket','other'=>'package'][$item->category] ?? 'package' }}" class="w-5 h-5"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium truncate">{{ $item->title }}</p>
                    <p class="text-xs text-gray-500">{{ $item->conversations_count }} chats · {{ ucfirst($item->status) }}</p>
                </div>
                <span class="text-brand-400 font-semibold text-sm">৳{{ number_format($item->price, 2) }}</span>
            </a>
            @empty
            <p class="text-gray-500 text-sm text-center py-8">No listings yet. <a href="{{ route('marketplace.create') }}" class="text-brand-400">Create one!</a></p>
            @endforelse
        </div>

        {{-- Recent Transactions --}}
        <div class="glass rounded-xl p-5">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-semibold text-lg">Recent Transactions</h3>
                <a href="{{ route('transactions.index') }}" class="text-xs text-brand-400 hover:text-brand-300 transition">View All</a>
            </div>
            @forelse($recentTransactions as $tx)
            <a href="{{ route('transactions.show', $tx) }}" class="flex items-center gap-3 p-3 rounded-lg hover:bg-white/5 transition mb-1">
                <div class="w-10 h-10 rounded-lg flex items-center justify-center flex-shrink-0 {{ $tx->status === 'completed' ? 'bg-green-500/10 text-green-400' : ($tx->status === 'cancelled' ? 'bg-red-500/10 text-red-400' : 'bg-yellow-500/10 text-yellow-400') }}">
                    <i data-lucide="{{ $tx->status === 'completed' ? 'check-circle' : ($tx->status === 'cancelled' ? 'x-circle' : 'clock') }}" class="w-5 h-5"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium truncate">{{ $tx->item->title ?? 'Deleted Item' }}</p>
                    <p class="text-xs text-gray-500">{{ $tx->buyer_id === auth()->id() ? 'Purchased from ' . $tx->seller->name : 'Sold to ' . $tx->buyer->name }}</p>
                </div>
                <span class="text-sm font-semibold {{ $tx->buyer_id === auth()->id() ? 'text-red-400' : 'text-green-400' }}">৳{{ number_format($tx->amount, 2) }}</span>
            </a>
            @empty
            <p class="text-gray-500 text-sm text-center py-8">No transactions yet.</p>
            @endforelse
        </div>
    </div>

    {{-- Quick Actions --}}
    <div class="glass rounded-xl p-5">
        <h3 class="font-semibold text-lg mb-4">Quick Actions</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-3">
            <a href="{{ route('marketplace.index') }}" class="p-4 rounded-xl bg-blue-500/5 border border-blue-500/10 hover:border-blue-500/30 transition text-center group">
                <i data-lucide="shopping-bag" class="w-8 h-8 mx-auto mb-2 text-blue-400 group-hover:scale-110 transition"></i>
                <span class="text-sm font-medium text-gray-300 group-hover:text-white transition">Marketplace</span>
            </a>
            <a href="{{ route('marketplace.create') }}" class="p-4 rounded-xl bg-green-500/5 border border-green-500/10 hover:border-green-500/30 transition text-center group">
                <i data-lucide="plus-circle" class="w-8 h-8 mx-auto mb-2 text-green-400 group-hover:scale-110 transition"></i>
                <span class="text-sm font-medium text-gray-300 group-hover:text-white transition">Sell Item</span>
            </a>
            <a href="{{ route('rides.index') }}" class="p-4 rounded-xl bg-cyan-500/5 border border-cyan-500/10 hover:border-cyan-500/30 transition text-center group">
                <i data-lucide="car" class="w-8 h-8 mx-auto mb-2 text-cyan-400 group-hover:scale-110 transition"></i>
                <span class="text-sm font-medium text-gray-300 group-hover:text-white transition">Ride Sharing</span>
            </a>
            <a href="{{ route('tutors.index') }}" class="p-4 rounded-xl bg-violet-500/5 border border-violet-500/10 hover:border-violet-500/30 transition text-center group">
                <i data-lucide="graduation-cap" class="w-8 h-8 mx-auto mb-2 text-violet-400 group-hover:scale-110 transition"></i>
                <span class="text-sm font-medium text-gray-300 group-hover:text-white transition">Tutors</span>
            </a>
            <a href="{{ route('tutoring-sessions.index') }}" class="p-4 rounded-xl bg-pink-500/5 border border-pink-500/10 hover:border-pink-500/30 transition text-center group">
                <i data-lucide="calendar-check" class="w-8 h-8 mx-auto mb-2 text-pink-400 group-hover:scale-110 transition"></i>
                <span class="text-sm font-medium text-gray-300 group-hover:text-white transition">Sessions</span>
            </a>
            <a href="{{ route('doubt-forum.index') }}" class="p-4 rounded-xl bg-indigo-500/5 border border-indigo-500/10 hover:border-indigo-500/30 transition text-center group">
                <i data-lucide="messages-square" class="w-8 h-8 mx-auto mb-2 text-indigo-400 group-hover:scale-110 transition"></i>
                <span class="text-sm font-medium text-gray-300 group-hover:text-white transition">Doubt Forum</span>
            </a>
            <a href="{{ route('karma.leaderboard') }}" class="p-4 rounded-xl bg-amber-500/5 border border-amber-500/10 hover:border-amber-500/30 transition text-center group">
                <i data-lucide="award" class="w-8 h-8 mx-auto mb-2 text-amber-400 group-hover:scale-110 transition"></i>
                <span class="text-sm font-medium text-gray-300 group-hover:text-white transition">Leaderboard</span>
            </a>
            <a href="{{ route('karma.sustainability') }}" class="p-4 rounded-xl bg-emerald-500/5 border border-emerald-500/10 hover:border-emerald-500/30 transition text-center group">
                <i data-lucide="leaf" class="w-8 h-8 mx-auto mb-2 text-emerald-400 group-hover:scale-110 transition"></i>
                <span class="text-sm font-medium text-gray-300 group-hover:text-white transition">Eco Impact</span>
            </a>
        </div>
    </div>
</div>
@endsection
