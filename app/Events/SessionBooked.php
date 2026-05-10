<?php

namespace App\Events;

use App\Models\TutoringSession;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SessionBooked
{
    use Dispatchable, SerializesModels;

    public function __construct(public TutoringSession $session)
    {
    }
}
