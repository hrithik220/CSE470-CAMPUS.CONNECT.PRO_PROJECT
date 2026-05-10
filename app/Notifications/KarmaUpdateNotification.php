<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class KarmaUpdateNotification extends Notification
{
    use Queueable;

    public function __construct(
        protected int $points,
        protected string $reason
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $sign = $this->points > 0 ? '+' : '';
        return [
            'type' => 'karma_update',
            'message' => "{$sign}{$this->points} karma: {$this->reason}",
            'points' => $this->points,
            'reason' => $this->reason,
        ];
    }
}
