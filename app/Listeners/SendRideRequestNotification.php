<?php

namespace App\Listeners;

use App\Events\RideRequestReceived;
use App\Services\NotificationService;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendRideRequestNotification implements ShouldQueue
{
    public function __construct(protected NotificationService $notificationService)
    {
    }

    public function handle(RideRequestReceived $event): void
    {
        $rideRequest = $event->rideRequest;
        $ride = $rideRequest->ride;
        $driver = $ride->driver;
        $passenger = $rideRequest->passenger;

        $this->notificationService->notifyRideRequest($driver, $passenger, $ride);
    }
}
