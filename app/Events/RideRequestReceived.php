<?php

namespace App\Events;

use App\Models\RideRequest;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RideRequestReceived
{
    use Dispatchable, SerializesModels;

    public function __construct(public RideRequest $rideRequest)
    {
    }
}
