<?php

namespace App\Events;

use App\Models\RideRequest;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RideRequestAccepted
{
    use Dispatchable, SerializesModels;

    public function __construct(public RideRequest $rideRequest)
    {
    }
}
