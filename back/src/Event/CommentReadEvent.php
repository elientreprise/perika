<?php

namespace App\Event;

use Symfony\Contracts\EventDispatcher\Event;

class CommentReadEvent extends Event
{
    public const string NAME = 'comment.read';

    public function __construct(
        private readonly array $comments,
    ) {
    }

    public function getComments(): array
    {
        return $this->comments;
    }
}
