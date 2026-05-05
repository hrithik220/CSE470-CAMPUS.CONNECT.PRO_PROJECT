<?php

namespace App\Http\Controllers\Marketplace;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\Transaction;
use App\Services\KarmaService;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    protected KarmaService $karmaService;

    public function __construct(KarmaService $karmaService)
    {
        $this->karmaService = $karmaService;
    }

    public function initiate(Request $request, Item $item)
    {
        if ($item->seller_id === auth()->id()) {
            return back()->with('error', 'You cannot buy your own item.');
        }
        if ($item->status !== 'available') {
            return back()->with('error', 'This item is no longer available.');
        }

        $transaction = Transaction::create([
            'item_id' => $item->id,
            'buyer_id' => auth()->id(),
            'seller_id' => $item->seller_id,
            'amount' => $item->price,
            'status' => 'pending',
            'notes' => $request->notes,
        ]);

        $item->update(['status' => 'reserved']);

        return redirect()->route('transactions.show', $transaction)
            ->with('success', 'Transaction initiated! Waiting for seller confirmation.');
    }

    public function show(Transaction $transaction)
    {
        $user = auth()->user();
        if ($transaction->buyer_id !== $user->id && $transaction->seller_id !== $user->id && !$user->isAdmin()) {
            abort(403);
        }
        $transaction->load(['item.images', 'buyer', 'seller', 'review']);
        return view('marketplace.transaction', compact('transaction'));
    }

    public function complete(Transaction $transaction)
    {
        if ($transaction->seller_id !== auth()->id()) {
            abort(403, 'Only the seller can complete this transaction.');
        }
        $transaction->markCompleted();
        $this->karmaService->awardForSale($transaction);

        return back()->with('success', 'Transaction completed! Karma awarded.');
    }

    public function cancel(Transaction $transaction)
    {
        $user = auth()->user();
        if ($transaction->buyer_id !== $user->id && $transaction->seller_id !== $user->id) {
            abort(403);
        }
        $transaction->update(['status' => 'cancelled']);
        $transaction->item->update(['status' => 'available']);

        return back()->with('success', 'Transaction cancelled.');
    }

    public function history()
    {
        $user = auth()->user();
        $transactions = Transaction::where('buyer_id', $user->id)
            ->orWhere('seller_id', $user->id)
            ->with(['item.images', 'buyer', 'seller'])
            ->latest()
            ->paginate(15);

        return view('marketplace.transactions', compact('transactions'));
    }
}
