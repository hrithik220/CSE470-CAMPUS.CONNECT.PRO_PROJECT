<?php

namespace App\Http\Controllers\Marketplace;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    /**
     * Show all conversations for the authenticated user.
     */
    public function index(Request $request)
    {
        $user = $request->user();

        // Get unique conversation partners
        $conversations = Message::where('sender_id', $user->id)
            ->orWhere('receiver_id', $user->id)
            ->latest()
            ->get()
            ->groupBy(function ($message) use ($user) {
                $otherId = $message->sender_id === $user->id
                    ? $message->receiver_id
                    : $message->sender_id;
                return $otherId . '-' . ($message->listing_id ?? 0);
            })
            ->map(function ($messages) use ($user) {
                $latest = $messages->first();
                $otherId = $latest->sender_id === $user->id
                    ? $latest->receiver_id
                    : $latest->sender_id;
                return [
                    'other_user' => User::find($otherId),
                    'listing' => $latest->listing,
                    'latest_message' => $latest,
                    'unread_count' => $messages->where('receiver_id', $user->id)->whereNull('read_at')->count(),
                ];
            });

        return view('marketplace.messages.index', compact('conversations'));
    }

    /**
     * Show conversation with a specific user about a listing.
     */
    public function show(Request $request, User $otherUser, ?int $listingId = null)
    {
        $user = $request->user();

        $messages = Message::conversation($user->id, $otherUser->id, $listingId)
            ->with(['sender', 'receiver'])
            ->orderBy('created_at')
            ->get();

        // Mark messages as read
        Message::where('sender_id', $otherUser->id)
            ->where('receiver_id', $user->id)
            ->when($listingId, fn($q) => $q->where('listing_id', $listingId))
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        $listing = $listingId ? \App\Models\Listing::find($listingId) : null;

        return view('marketplace.messages.show', compact('messages', 'otherUser', 'listing'));
    }

    /**
     * Send a message.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'listing_id' => 'nullable|exists:listings,id',
            'body' => 'required|string|max:1000',
        ]);

        $message = Message::create([
            'sender_id' => auth()->id(),
            'receiver_id' => $validated['receiver_id'],
            'listing_id' => $validated['listing_id'] ?? null,
            'body' => $validated['body'],
        ]);

        if ($request->wantsJson()) {
            return response()->json($message->load('sender'));
        }

        return back()->with('success', 'Message sent!');
    }
}
