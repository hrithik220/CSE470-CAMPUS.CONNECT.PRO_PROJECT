@extends('layouts.app')
@section('title', 'Notifications — Campus Connect Pro')
@section('page_title', 'Notifications')

@section('content')
<div class="max-w-2xl mx-auto fade-in space-y-4">
    @if(auth()->user()->unreadNotifications->count())
    <form method="POST" action="{{ route('notifications.mark-read') }}" class="text-right">
        @csrf
        <button class="text-xs text-brand-400 hover:text-brand-300 transition">Mark all as read</button>
    </form>
    @endif

    @forelse($notifications as $notification)
    <div class="glass rounded-xl p-4 flex items-center gap-4 {{ $notification->read_at ? 'opacity-60' : '' }}">
        <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0
            {{ ($notification->data['type'] ?? '') === 'new_message' ? 'bg-blue-500/10 text-blue-400' : (($notification->data['type'] ?? '') === 'review_received' ? 'bg-yellow-500/10 text-yellow-400' : 'bg-brand-500/10 text-brand-400') }}">
            <i data-lucide="{{ ($notification->data['type'] ?? '') === 'new_message' ? 'message-square' : (($notification->data['type'] ?? '') === 'review_received' ? 'star' : 'zap') }}" class="w-5 h-5"></i>
        </div>
        <div class="flex-1">
            <p class="text-sm">{{ $notification->data['message'] ?? 'Notification' }}</p>
            <p class="text-[10px] text-gray-500 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
        </div>
        @if(!$notification->read_at)
        <span class="w-2 h-2 bg-brand-500 rounded-full flex-shrink-0"></span>
        @endif
    </div>
    @empty
    <div class="text-center py-16">
        <div class="w-16 h-16 bg-white/5 rounded-2xl flex items-center justify-center mx-auto mb-4 text-gray-500">
            <i data-lucide="bell-off" class="w-8 h-8"></i>
        </div>
        <p class="text-gray-400">No notifications yet.</p>
    </div>
    @endforelse
    {{ $notifications->links() }}
</div>
@endsection
