@extends('layouts.app')
@section('title', 'Messages')
@section('header', 'Messages')
@section('content')
<div class="max-w-3xl mx-auto space-y-4">
    @forelse($conversations as $conv)
    <a href="{{ route('messages.show', [$conv['other_user'], $conv['listing']?->id]) }}" class="glass rounded-xl p-4 flex items-center gap-4 card-hover block">
        <img src="{{ $conv['other_user']->avatar_url }}" class="w-12 h-12 rounded-full" alt="">
        <div class="flex-1 min-w-0">
            <div class="flex items-center gap-2">
                <p class="font-medium">{{ $conv['other_user']->name }}</p>
                @if($conv['listing'])<span class="text-xs text-gray-400">· {{ $conv['listing']->title }}</span>@endif
            </div>
            <p class="text-sm text-gray-400 truncate">{{ $conv['latest_message']->body }}</p>
        </div>
        <div class="text-right">
            <p class="text-xs text-gray-500">{{ $conv['latest_message']->created_at->diffForHumans() }}</p>
            @if($conv['unread_count'] > 0)<span class="inline-block bg-indigo-500 text-white text-xs px-2 py-0.5 rounded-full mt-1">{{ $conv['unread_count'] }}</span>@endif
        </div>
    </a>
    @empty
    <div class="text-center py-12"><p class="text-4xl mb-4">✉️</p><p class="text-gray-400">No messages yet</p></div>
    @endforelse
</div>
@endsection
