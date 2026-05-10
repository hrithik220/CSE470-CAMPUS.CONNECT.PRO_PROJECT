<?php

namespace App\Listeners;

use App\Events\ForumAnswerPosted;
use App\Services\NotificationService;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendForumAnswerNotification implements ShouldQueue
{
    public function __construct(protected NotificationService $notificationService)
    {
    }

    public function handle(ForumAnswerPosted $event): void
    {
        $answer = $event->answer;
        $post = $answer->post;
        $postAuthor = $post->author;

        // Don't notify if user answered their own question
        if ($answer->user_id === $post->user_id) {
            return;
        }

        $answererName = $answer->is_anonymous ? 'Someone' : $answer->author->name;

        $this->notificationService->notify(
            $postAuthor,
            'New Answer to Your Question',
            "{$answererName} answered your question: '{$post->title}'",
            'forum',
            route('forum.show', $post)
        );
    }
}
