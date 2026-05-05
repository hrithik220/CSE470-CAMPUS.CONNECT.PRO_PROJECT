@extends('layouts.app')
@section('title', 'Messages — Campus Connect Pro')
@section('page_title', 'Messages')

@section('content')
<div class="max-w-3xl mx-auto fade-in space-y-2">
    @forelse($conversations as $convo)
    @php $other = $convo->getOtherParticipant(auth()->user()); @endphp
    <a href="{{ route('chat.show', $convo) }}" class="glass rounded-xl p-4 flex items-center gap-4 card-hover block">
        <div class="relative">
            <img src="{{ $other->avatar_url }}" class="w-12 h-12 rounded-full ring-2 ring-brand-500/20" alt="">
            @if($convo->messages_count > 0)
            <span class="absolute -top-1 -right-1 w-5 h-5 bg-brand-500 rounded-full text-[10px] font-bold text-white flex items-center justify-center">{{ $convo->messages_count }}</span>
            @endif
        </div>
        <div class="flex-1 min-w-0">
            <div class="flex items-center justify-between">
                <p class="font-semibold text-sm">{{ $other->name }}</p>
                <span class="text-[10px] text-gray-500">{{ $convo->last_message_at?->diffForHumans() }}</span>
            </div>
            <p class="text-xs text-gray-500 truncate">{{ $convo->item->title }}</p>
            @if($convo->latestMessage)
            <p class="text-xs text-gray-400 truncate mt-1">{{ $convo->latestMessage->body }}</p>
            @endif
        </div>
    </a>
    @empty
    <div class="text-center py-16">
        <p class="text-4xl mb-3">💬</p>
        <p class="text-gray-400 font-medium">No conversations yet</p>
        <p class="text-gray-500 text-sm mt-1">Start chatting by messaging a seller from the <a href="{{ route('marketplace.index') }}" class="text-brand-400">marketplace</a>.</p>
    </div>
    @endforelse
    {{ $conversations->links() }}
</div>
@endsection
