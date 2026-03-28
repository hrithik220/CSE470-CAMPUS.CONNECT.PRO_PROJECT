@extends('layouts.app')
@section('title', 'Chat — ' . $item->title)

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="flex items-center gap-3 mb-4">
        <a href="{{ route('marketplace.show', $item) }}" class="text-blue-600 hover:underline text-sm">
            ← Back to Item
        </a>
        <span class="text-gray-400">|</span>
        <h1 class="text-lg font-bold text-gray-800">Chat about: {{ $item->title }}</h1>
    </div>

    <div class="bg-white rounded-lg shadow flex flex-col" style="height: 600px;">

        {{-- Chat Header --}}
        <div class="bg-blue-600 text-white px-5 py-3 rounded-t-lg flex justify-between items-center">
            <div>
                <p class="font-semibold">{{ $item->title }}</p>
                <p class="text-sm text-blue-200">Seller: {{ $item->seller->name }}</p>
            </div>
            <span class="text-sm bg-white text-blue-600 px-2 py-1 rounded font-medium">
                ৳{{ number_format($item->price, 2) }}
            </span>
        </div>

        {{-- Messages --}}
        <div class="flex-1 overflow-y-auto p-5 space-y-4" id="chat-box">
            @forelse($messages as $msg)
                <div class="flex {{ $msg->sender_id === Auth::id() ? 'justify-end' : 'justify-start' }}">
                    <div class="max-w-xs lg:max-w-md">
                        <div class="{{ $msg->sender_id === Auth::id()
                            ? 'bg-blue-600 text-white rounded-tl-2xl rounded-tr-sm rounded-b-2xl'
                            : 'bg-gray-100 text-gray-800 rounded-tr-2xl rounded-tl-sm rounded-b-2xl' }}
                            px-4 py-2 text-sm shadow-sm">
                            {{ $msg->message }}
                        </div>
                        <p class="text-xs text-gray-400 mt-1
                            {{ $msg->sender_id === Auth::id() ? 'text-right' : 'text-left' }}">
                            {{ $msg->sender->name }} · {{ $msg->created_at->format('h:i A') }}
                        </p>
                    </div>
                </div>
            @empty
                <div class="text-center text-gray-400 py-10">
                    No messages yet. Start the conversation!
                </div>
            @endforelse
        </div>

        {{-- Message Input --}}
        @if(Auth::id() !== $item->seller_id || $item->seller_id === Auth::id())
        <div class="border-t p-4">
            <form method="POST"
                  action="{{ route('marketplace.chat.store', $item) }}"
                  class="flex gap-3">
                @csrf

                {{-- Receiver: if current user is seller, receiver is buyer; else receiver is seller --}}
                @if(Auth::id() === $item->seller_id)
                    {{-- Seller replying — receiver is whoever sent the last message --}}
                    @php $lastMsg = $messages->last(); @endphp
                    @if($lastMsg)
                        <input type="hidden" name="receiver_id"
                               value="{{ $lastMsg->sender_id === Auth::id() ? $lastMsg->receiver_id : $lastMsg->sender_id }}">
                    @endif
                @else
                    <input type="hidden" name="receiver_id" value="{{ $item->seller_id }}">
                @endif

                <input type="text" name="message"
                       placeholder="Type a message..."
                       autocomplete="off"
                       class="flex-1 border rounded-full px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                <button type="submit"
                        class="bg-blue-600 text-white px-5 py-2 rounded-full hover:bg-blue-700 text-sm font-medium transition">
                    Send
                </button>
            </form>
        </div>
        @endif
    </div>
</div>

<script>
    // Auto-scroll chat to bottom
    const chatBox = document.getElementById('chat-box');
    chatBox.scrollTop = chatBox.scrollHeight;
</script>
@endsection
