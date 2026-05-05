@extends('layouts.app')
@section('title', 'Chat with ' . $otherUser->name)
@section('header', 'Chat with ' . $otherUser->name)
@section('content')
<div class="max-w-3xl mx-auto">
    @if($listing)<div class="glass rounded-xl p-3 mb-4 flex items-center gap-3">
        <span class="text-lg">📦</span><span class="text-sm">Re: <strong>{{ $listing->title }}</strong> — ${{ $listing->price }}</span>
    </div>@endif
    <div class="glass rounded-2xl p-6 mb-4 h-96 overflow-y-auto scroll-hidden space-y-3" id="chat-box">
        @foreach($messages as $msg)
        <div class="flex {{ $msg->sender_id === auth()->id() ? 'justify-end' : 'justify-start' }}">
            <div class="max-w-xs px-4 py-2 rounded-2xl {{ $msg->sender_id === auth()->id() ? 'bg-indigo-500/30 text-white' : 'bg-white/10 text-gray-200' }}">
                <p class="text-sm">{{ $msg->body }}</p>
                <p class="text-xs text-gray-500 mt-1">{{ $msg->created_at->format('g:i A') }}</p>
            </div>
        </div>
        @endforeach
    </div>
    <form method="POST" action="{{ route('messages.store') }}" class="glass rounded-2xl p-4 flex gap-3">
        @csrf
        <input type="hidden" name="receiver_id" value="{{ $otherUser->id }}">
        <input type="hidden" name="listing_id" value="{{ $listing?->id }}">
        <input type="text" name="body" required placeholder="Type a message..." autofocus
            class="flex-1 px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-gray-500 focus:border-indigo-500 outline-none">
        <button type="submit" class="btn-primary text-white px-6 py-3 rounded-xl font-medium transition">Send</button>
    </form>
</div>
@section('scripts')
<script>document.getElementById('chat-box').scrollTop = document.getElementById('chat-box').scrollHeight;</script>
@endsection
@endsection
