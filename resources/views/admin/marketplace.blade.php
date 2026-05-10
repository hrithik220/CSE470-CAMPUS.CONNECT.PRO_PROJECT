@extends('layouts.app')
@section('title', 'Marketplace Monitor — Admin')
@section('page_title', 'Marketplace Monitor')

@section('content')
<div class="fade-in space-y-4">
    <form method="GET" class="glass rounded-xl p-4 flex flex-wrap gap-3">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search items..."
            class="flex-1 min-w-[200px] px-4 py-2 rounded-lg bg-white/5 border border-white/10 text-white placeholder-gray-500 text-sm focus:border-brand-500 outline-none">
        <select name="status" class="px-3 py-2 rounded-lg bg-white/5 border border-white/10 text-gray-300 text-sm">
            <option value="">All Status</option>
            @foreach(['available','sold','reserved','flagged'] as $s)
            <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
            @endforeach
        </select>
        <button class="px-5 py-2 bg-brand-600 text-white rounded-lg text-sm">Filter</button>
    </form>

    <div class="glass rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead><tr class="border-b border-white/5 text-left text-xs text-gray-500 uppercase">
                    <th class="p-4">Item</th><th class="p-4">Seller</th><th class="p-4">Price</th><th class="p-4">Category</th><th class="p-4">Status</th><th class="p-4">Views</th><th class="p-4">Actions</th>
                </tr></thead>
                <tbody>
                @foreach($items as $item)
                <tr class="border-b border-white/5 hover:bg-white/5 transition">
                    <td class="p-4"><a href="{{ route('marketplace.show', $item) }}" class="font-medium hover:text-brand-400 transition truncate block max-w-[200px]">{{ $item->title }}</a></td>
                    <td class="p-4 text-gray-400">{{ $item->seller->name ?? '—' }}</td>
                    <td class="p-4 text-brand-400 font-semibold">৳{{ number_format($item->price, 2) }}</td>
                    <td class="p-4 text-gray-400">{{ $item->category_label }}</td>
                    <td class="p-4"><span class="px-2 py-0.5 text-[10px] rounded-full {{ $item->status === 'flagged' ? 'bg-red-500/20 text-red-400' : ($item->status === 'available' ? 'bg-green-500/20 text-green-400' : 'bg-gray-500/20 text-gray-400') }}">{{ ucfirst($item->status) }}</span></td>
                    <td class="p-4 text-gray-500">{{ $item->views_count }}</td>
                    <td class="p-4">
                        @if($item->status !== 'flagged')
                        <form method="POST" action="{{ route('admin.marketplace.flag', $item) }}" class="inline" onsubmit="return confirm('Flag this item?')">@csrf
                            <button class="text-xs text-red-400 hover:text-red-300 flex items-center gap-1">
                                <i data-lucide="flag" class="w-3 h-3"></i> Flag
                            </button>
                        </form>
                        @else
                        <span class="text-xs text-gray-500">Flagged</span>
                        @endif
                    </td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    {{ $items->links() }}
</div>
@endsection
