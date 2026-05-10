<?php

namespace App\Listeners;

use App\Events\RideRequestAccepted;
use App\Services\NotificationService;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendRideAcceptedNotification implements ShouldQueue
{
    public function __construct(protected NotificationService $notificationService)
    {
    }

    public function handle(RideRequestAccepted $event): void
    {
        $passenger = $event->rideRequest->passenger;
        $ride = $event->rideRequest->ride;

        $this->notificationService->notify(
            $passenger,
            'Ride Request Accepted! 🎉',
            "Your request to join the ride from {$ride->pickup_location} to {$ride->destination} has been accepted.",
            'ride',
            route('rides.show', $ride)
        );
    }
}
