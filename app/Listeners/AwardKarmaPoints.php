<?php

namespace App\Listeners;

use App\Services\KarmaService;
use Illuminate\Contracts\Queue\ShouldQueue;

class AwardKarmaPoints implements ShouldQueue
{
    public function __construct(protected KarmaService $karmaService)
    {
    }

    public function handle(object $event): void
    {
        match (true) {
            $event instanceof \App\Events\RideRequestReceived => $this->karmaService->award(
                $event->rideRequest->passenger, 'ride_join', $event->rideRequest->ride
            ),
            $event instanceof \App\Events\RideRequestAccepted => $this->karmaService->award(
                $event->rideRequest->ride->driver, 'ride_share', $event->rideRequest->ride
            ),
            $event instanceof \App\Events\ListingCreated => $this->karmaService->award(
                $event->listing->seller, 'listing_create', $event->listing
            ),
            $event instanceof \App\Events\TransactionCompleted => $this->karmaService->award(
                $event->transaction->seller, 'listing_sell', $event->transaction
            ),
            $event instanceof \App\Events\SessionBooked => $this->karmaService->award(
                $event->session->tutor, 'tutor_session', $event->session
            ),
            $event instanceof \App\Events\ForumAnswerPosted => $this->karmaService->award(
                $event->answer->author, 'forum_answer', $event->answer
            ),
            $event instanceof \App\Events\ReviewSubmitted => $this->karmaService->award(
                $event->review->reviewer, 'review_given', $event->review
            ),
            default => null,
        };
    }
}
