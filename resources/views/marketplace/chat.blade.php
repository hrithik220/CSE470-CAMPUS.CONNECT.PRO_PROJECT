@extends('layouts.app')
@section('title', 'Chat — Campus Connect Pro')
@section('page_title', 'Chat with ' . $otherUser->name)

@section('content')
<div class="max-w-3xl mx-auto fade-in flex flex-col" style="height: calc(100vh - 180px);">
    {{-- Item Banner --}}
    <div class="glass rounded-t-xl p-3 flex items-center gap-3 border-b border-white/5">
        <div class="w-10 h-10 rounded-lg bg-brand-500/10 flex items-center justify-center text-brand-400">
            <i data-lucide="package" class="w-5 h-5"></i>
        </div>
        <div class="flex-1 min-w-0">
            <a href="{{ route('marketplace.show', $conversation->item) }}" class="text-sm font-medium hover:text-brand-400 transition truncate block">{{ $conversation->item->title }}</a>
            <p class="text-xs text-gray-500">৳{{ number_format($conversation->item->price, 2) }}</p>
        </div>
        <img src="{{ $otherUser->avatar_url }}" class="w-8 h-8 rounded-full ring-2 ring-brand-500/20" alt="">
    </div>

    {{-- Messages --}}
    <div id="chatMessages" class="flex-1 overflow-y-auto p-4 space-y-3 bg-surface-900/50">
        @foreach($messages as $msg)
        <div class="flex {{ $msg->sender_id === auth()->id() ? 'justify-end' : 'justify-start' }}">
            <div class="max-w-[75%] {{ $msg->sender_id === auth()->id() ? 'bg-brand-600/30 border-brand-500/20' : 'bg-white/5 border-white/10' }} border rounded-2xl px-4 py-2.5">
                <p class="text-sm">{{ $msg->body }}</p>
                <p class="text-[10px] text-gray-500 mt-1 {{ $msg->sender_id === auth()->id() ? 'text-right' : '' }}">{{ $msg->created_at->diffForHumans() }}</p>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Input --}}
    <form id="chatForm" method="POST" action="{{ route('chat.send', $conversation) }}" class="glass rounded-b-xl p-3 flex gap-2">
        @csrf
        <input type="text" name="body" id="msgInput" required maxlength="2000" autocomplete="off"
            class="flex-1 px-4 py-2.5 rounded-xl bg-white/5 border border-white/10 text-white placeholder-gray-500 focus:border-brand-500 outline-none transition text-sm"
            placeholder="Type a message...">
        <button type="submit" class="px-5 py-2.5 bg-brand-600 hover:bg-brand-500 text-white font-medium rounded-xl transition text-sm flex items-center gap-2">
            <i data-lucide="send" class="w-4 h-4"></i> Send
        </button>
    </form>
</div>

@push('scripts')
<script>
const chatDiv = document.getElementById('chatMessages');
chatDiv.scrollTop = chatDiv.scrollHeight;

let lastId = {{ $messages->last()?->id ?? 0 }};
const convId = {{ $conversation->id }};

// AJAX polling for new messages
setInterval(async () => {
    try {
        const res = await fetch(`/chat/${convId}/fetch?last_id=${lastId}`);
        const data = await res.json();
        data.messages.forEach(msg => {
            if (msg.id > lastId) {
                lastId = msg.id;
                const div = document.createElement('div');
                div.className = 'flex ' + (msg.is_mine ? 'justify-end' : 'justify-start');
                div.innerHTML = `<div class="max-w-[75%] ${msg.is_mine ? 'bg-brand-600/30 border-brand-500/20' : 'bg-white/5 border-white/10'} border rounded-2xl px-4 py-2.5"><p class="text-sm">${msg.body}</p><p class="text-[10px] text-gray-500 mt-1 ${msg.is_mine ? 'text-right' : ''}">${msg.created_at}</p></div>`;
                chatDiv.appendChild(div);
                chatDiv.scrollTop = chatDiv.scrollHeight;
            }
        });
    } catch(e) {}
}, 3000);

// AJAX send
document.getElementById('chatForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    const input = document.getElementById('msgInput');
    const body = input.value.trim();
    if (!body) return;
    input.value = '';
    const token = document.querySelector('meta[name="csrf-token"]').content;
    try {
        const res = await fetch(`/chat/${convId}/send`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': token, 'X-Requested-With': 'XMLHttpRequest' },
            body: JSON.stringify({ body })
        });
        const data = await res.json();
        if (data.success) {
            const msg = data.message;
            lastId = msg.id;
            const div = document.createElement('div');
            div.className = 'flex justify-end';
            div.innerHTML = `<div class="max-w-[75%] bg-brand-600/30 border-brand-500/20 border rounded-2xl px-4 py-2.5"><p class="text-sm">${msg.body}</p><p class="text-[10px] text-gray-500 mt-1 text-right">${msg.created_at}</p></div>`;
            chatDiv.appendChild(div);
            chatDiv.scrollTop = chatDiv.scrollHeight;
        }
    } catch(e) {}
});
</script>
@endpush
@endsection
