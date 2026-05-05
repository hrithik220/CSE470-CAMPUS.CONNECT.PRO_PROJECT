<?php

namespace App\Http\Controllers\Marketplace;

use App\Http\Controllers\Controller;
use App\Http\Requests\SendMessageRequest;
use App\Models\Conversation;
use App\Models\Item;
use App\Models\Message;
use App\Notifications\NewMessageNotification;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    /**
     * List all conversations for the authenticated user.
     */
    public function index()
    {
        $user = auth()->user();

        $conversations = Conversation::where('buyer_id', $user->id)
            ->orWhere('seller_id', $user->id)
            ->with(['item.images', 'buyer', 'seller', 'latestMessage'])
            ->withCount(['messages' => function ($q) use ($user) {
                $q->where('sender_id', '!=', $user->id)->where('is_read', false);
            }])
            ->orderByDesc('last_message_at')
            ->paginate(20);

        return view('marketplace.conversations', compact('conversations'));
    }

    /**
     * Start or open a conversation for an item.
     */
    public function startConversation(Item $item)
    {
        if ($item->seller_id === auth()->id()) {
            return back()->with('error', 'You cannot message yourself.');
        }

        $conversation = Conversation::findOrCreateForItem($item, auth()->user());

        return redirect()->route('chat.show', $conversation);
    }

    /**
     * Show a conversation with all messages.
     */
    public function show(Conversation $conversation)
    {
        $user = auth()->user();

        // Ensure user is part of the conversation
        if ($conversation->buyer_id !== $user->id && $conversation->seller_id !== $user->id) {
            abort(403, 'You are not part of this conversation.');
        }

        // Mark unread messages as read
        $conversation->messages()
            ->where('sender_id', '!=', $user->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        $messages = $conversation->messages()->with('sender')->get();
        $otherUser = $conversation->getOtherParticipant($user);

        $conversation->load('item.images');

        return view('marketplace.chat', compact('conversation', 'messages', 'otherUser'));
    }

    /**
     * Send a message in a conversation.
     */
    public function sendMessage(SendMessageRequest $request, Conversation $conversation)
    {
        $user = auth()->user();

        if ($conversation->buyer_id !== $user->id && $conversation->seller_id !== $user->id) {
            abort(403);
        }

        $message = $conversation->messages()->create([
            'sender_id' => $user->id,
            'body' => $request->body,
        ]);

        $conversation->update(['last_message_at' => now()]);

        // Notify the other participant
        $otherUser = $conversation->getOtherParticipant($user);
        $otherUser->notify(new NewMessageNotification($message, $user));

        // If AJAX request, return JSON
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => [
                    'id' => $message->id,
                    'body' => $message->body,
                    'sender_name' => $user->name,
                    'sender_avatar' => $user->avatar_url,
                    'is_mine' => true,
                    'created_at' => $message->created_at->diffForHumans(),
                ],
            ]);
        }

        return back();
    }

    /**
     * Fetch new messages via AJAX (polling).
     */
    public function fetchMessages(Request $request, Conversation $conversation)
    {
        $user = auth()->user();

        if ($conversation->buyer_id !== $user->id && $conversation->seller_id !== $user->id) {
            abort(403);
        }

        $lastId = $request->get('last_id', 0);

        $messages = $conversation->messages()
            ->where('id', '>', $lastId)
            ->with('sender')
            ->get()
            ->map(function ($msg) use ($user) {
                return [
                    'id' => $msg->id,
                    'body' => $msg->body,
                    'sender_name' => $msg->sender->name,
                    'sender_avatar' => $msg->sender->avatar_url,
                    'is_mine' => $msg->sender_id === $user->id,
                    'created_at' => $msg->created_at->diffForHumans(),
                ];
            });

        // Mark as read
        $conversation->messages()
            ->where('sender_id', '!=', $user->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json(['messages' => $messages]);
    }
}
