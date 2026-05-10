<?php

namespace App\Events;

use App\Models\ForumAnswer;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ForumAnswerPosted
{
    use Dispatchable, SerializesModels;

    public function __construct(public ForumAnswer $answer)
    {
    }
}
