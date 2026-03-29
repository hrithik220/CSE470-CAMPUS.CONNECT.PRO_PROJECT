@extends('layouts.app')
@section('title', 'Inbox')

@section('content')
<div class="max-w-3xl mx-auto">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">My Inbox</h1>

    @if($conversations->isEmpty())
        <div class="bg-white rounded-lg shadow p-8 text-center text-gray-400">
            No conversations yet.
        </div>
    @else
        <div class="space-y-4">
            @foreach($conversations as $itemId => $msgs)
                @php $item = $msgs->first()->item; @endphp
                <a href="{{ route('marketplace.chat', $item) }}"
                   class="bg-white rounded-lg shadow p-4 flex justify-between items-center hover:shadow-md transition block">
                    <div>
                        <p class="font-semibold text-gray-800">{{ $item->title }}</p>
                        <p class="text-sm text-gray-500">{{ $msgs->last()->message }}</p>
                    </div>
                    <span class="text-xs text-gray-400">{{ $msgs->last()->created_at->diffForHumans() }}</span>
                </a>
            @endforeach
        </div>
    @endif
</div>
@endsection