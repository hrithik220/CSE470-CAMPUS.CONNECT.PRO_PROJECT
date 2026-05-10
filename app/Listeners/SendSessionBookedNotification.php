<?php

namespace App\Listeners;

use App\Events\SessionBooked;
use App\Services\NotificationService;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendSessionBookedNotification implements ShouldQueue
{
    public function __construct(protected NotificationService $notificationService)
    {
    }

    public function handle(SessionBooked $event): void
    {
        $this->notificationService->notifySessionBooked(
            $event->session->tutor,
            $event->session->student,
            $event->session
        );
    }
}
