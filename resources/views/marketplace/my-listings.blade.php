@extends('layouts.app')
@section('title', 'My Listings — Campus Connect Pro')
@section('page_title', 'My Listings')

@section('content')
<div class="fade-in space-y-4">
    <div class="flex justify-between items-center">
        <p class="text-gray-400 text-sm">{{ $items->total() }} listings</p>
        <a href="{{ route('marketplace.create') }}" class="px-4 py-2 bg-brand-600 hover:bg-brand-500 text-white text-sm font-medium rounded-lg transition">+ New Listing</a>
    </div>
    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse($items as $item)
        <div class="glass rounded-xl overflow-hidden card-hover">
            <div class="aspect-[4/3] bg-surface-800">
                @if($item->images->count())
                <img src="{{ asset('storage/' . $item->images->first()->image_path) }}" class="w-full h-full object-cover">
                @else
                <div class="w-full h-full flex items-center justify-center text-brand-400">
                    <i data-lucide="package" class="w-12 h-12 opacity-30"></i>
                </div>
                @endif
            </div>
            <div class="p-4">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-lg font-bold text-brand-400">৳{{ number_format($item->price, 2) }}</span>
                    <span class="px-2 py-0.5 text-[10px] font-bold uppercase rounded {{ $item->status === 'available' ? 'bg-green-500/10 text-green-400' : ($item->status === 'sold' ? 'bg-gray-500/10 text-gray-400' : 'bg-yellow-500/10 text-yellow-400') }}">{{ $item->status }}</span>
                </div>
                <h3 class="text-sm font-semibold truncate">{{ $item->title }}</h3>
                <p class="text-xs text-gray-500 mt-1">{{ $item->conversations_count }} inquiries</p>
                <div class="flex gap-2 mt-3">
                    <a href="{{ route('marketplace.show', $item) }}" class="flex-1 py-2 text-center text-xs bg-white/5 hover:bg-white/10 rounded-lg transition">View</a>
                    <a href="{{ route('marketplace.edit', $item) }}" class="flex-1 py-2 text-center text-xs bg-brand-500/10 hover:bg-brand-500/20 text-brand-400 rounded-lg transition">Edit</a>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full text-center py-16">
            <div class="w-16 h-16 bg-white/5 rounded-2xl flex items-center justify-center mx-auto mb-4 text-gray-500">
                <i data-lucide="package" class="w-8 h-8"></i>
            </div>
            <p class="text-gray-400">You haven't listed any items yet.</p>
            <a href="{{ route('marketplace.create') }}" class="text-brand-400 text-sm">Create your first listing →</a>
        </div>
        @endforelse
    </div>
    {{ $items->links() }}
</div>
@endsection
