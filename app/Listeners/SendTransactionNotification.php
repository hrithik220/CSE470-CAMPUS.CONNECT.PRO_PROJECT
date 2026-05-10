<?php

namespace App\Listeners;

use App\Events\TransactionCompleted;
use App\Services\NotificationService;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendTransactionNotification implements ShouldQueue
{
    public function __construct(protected NotificationService $notificationService)
    {
    }

    public function handle(TransactionCompleted $event): void
    {
        $transaction = $event->transaction;

        $this->notificationService->notify(
            $transaction->buyer,
            'Transaction Completed',
            "Your purchase of '{$transaction->listing->title}' is complete. Please leave a review!",
            'marketplace',
            route('marketplace.show', $transaction->listing)
        );
    }
}
