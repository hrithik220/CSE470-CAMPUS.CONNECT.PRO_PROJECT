<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Transaction;
use App\Models\Conversation;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $myListings = Item::where('seller_id', $user->id)
            ->withCount('conversations')
            ->latest()
            ->take(5)
            ->get();

        $recentTransactions = Transaction::where('buyer_id', $user->id)
            ->orWhere('seller_id', $user->id)
            ->with(['item', 'buyer', 'seller'])
            ->latest()
            ->take(5)
            ->get();

        // SQLite does not support HAVING on this non-aggregate withCount query.
        // Load the unread count first, then filter the collection in PHP.
        $unreadMessages = Conversation::where(function ($q) use ($user) {
                $q->where('buyer_id', $user->id)->orWhere('seller_id', $user->id);
            })
            ->withCount(['messages' => function ($q) use ($user) {
                $q->where('sender_id', '!=', $user->id)->where('is_read', false);
            }])
            ->get()
            ->filter(function ($conversation) {
                return $conversation->messages_count > 0;
            });

        $stats = [
            'active_listings' => Item::where('seller_id', $user->id)->available()->count(),
            'total_sales' => Transaction::where('seller_id', $user->id)->completed()->count(),
            'total_purchases' => Transaction::where('buyer_id', $user->id)->completed()->count(),
            'karma_points' => $user->karma_points,
            'unread_messages' => $unreadMessages->sum('messages_count'),
        ];

        return view('dashboard', compact('myListings', 'recentTransactions', 'stats', 'unreadMessages'));
    }
}
