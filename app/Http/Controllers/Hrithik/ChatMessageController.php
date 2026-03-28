<?php

namespace App\Http\Controllers\Hrithik;

use App\Http\Controllers\Controller;
use App\Models\ChatMessage;
use App\Models\MarketplaceItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// ─────────────────────────────────────────────────────────────
// HRITHIK — Feature 2: In-app Chat Panel (Buyer ↔ Seller)
// ─────────────────────────────────────────────────────────────

class ChatMessageController extends Controller
{
    // Show chat panel for a specific item between buyer and seller
    public function show(MarketplaceItem $item)
    {
        $authUser = Auth::id();

        // Only the seller or the interested buyer can view the chat
        if ($authUser !== $item->seller_id) {
            // Buyer: load only messages between this buyer and the seller
            $messages = ChatMessage::where('item_id', $item->id)
                ->where(function ($query) use ($authUser, $item) {
                    $query->where('sender_id', $authUser)
                          ->orWhere('receiver_id', $authUser);
                })
                ->where(function ($query) use ($item) {
                    $query->where('sender_id', $item->seller_id)
                          ->orWhere('receiver_id', $item->seller_id);
                })
                ->with('sender', 'receiver')
                ->orderBy('created_at', 'asc')
                ->get();
        } else {
            // Seller: show all conversations grouped by buyer
            $messages = ChatMessage::where('item_id', $item->id)
                ->with('sender', 'receiver')
                ->orderBy('created_at', 'asc')
                ->get();
        }

        return view('marketplace.chat', compact('item', 'messages'));
    }

    // Send a new chat message
    public function store(Request $request, MarketplaceItem $item)
    {
        $request->validate([
            'message'     => 'required|string|max:1000',
            'receiver_id' => 'required|exists:users,id',
        ]);

        ChatMessage::create([
            'item_id'     => $item->id,
            'sender_id'   => Auth::id(),
            'receiver_id' => $request->receiver_id,
            'message'     => $request->message,
        ]);

        return redirect()->route('marketplace.chat', $item)
            ->with('success', 'Message sent!');
    }

    // Show all chats of logged-in user (inbox view)
    public function inbox()
    {
        $userId = Auth::id();

        // Get unique conversations: group by item_id + other user
        $conversations = ChatMessage::with('item', 'sender', 'receiver')
            ->where('sender_id', $userId)
            ->orWhere('receiver_id', $userId)
            ->latest()
            ->get()
            ->groupBy('item_id');

        return view('marketplace.inbox', compact('conversations'));
    }
}
