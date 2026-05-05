<?php

namespace App\Notifications;

use App\Models\Review;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReviewReceivedNotification extends Notification
{
    use Queueable;

    public function __construct(protected Review $review) {}

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New Review Received!')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('You received a ' . $this->review->rating . '-star review from ' . $this->review->reviewer->name)
            ->line('Comment: "' . ($this->review->comment ?? 'No comment') . '"')
            ->action('View Profile', url('/profile'))
            ->line('Keep up the great work!');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'review_received',
            'message' => $this->review->reviewer->name . ' gave you a ' . $this->review->rating . '-star review',
            'rating' => $this->review->rating,
            'reviewer_name' => $this->review->reviewer->name,
            'item_id' => $this->review->item_id,
        ];
    }
}
