<?php

namespace App\Notifications;

use App\Models\Message;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewMessageNotification extends Notification
{
    use Queueable;

    public function __construct(
        protected Message $message,
        protected User $sender
    ) {}

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New Message from ' . $this->sender->name)
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line($this->sender->name . ' sent you a message:')
            ->line('"' . \Illuminate\Support\Str::limit($this->message->body, 100) . '"')
            ->action('View Conversation', url('/chat/' . $this->message->conversation_id))
            ->line('Thank you for using Campus Connect Pro!');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'new_message',
            'message' => 'New message from ' . $this->sender->name,
            'sender_id' => $this->sender->id,
            'sender_name' => $this->sender->name,
            'conversation_id' => $this->message->conversation_id,
            'preview' => \Illuminate\Support\Str::limit($this->message->body, 50),
        ];
    }
}
