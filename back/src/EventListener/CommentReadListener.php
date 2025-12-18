<?php

namespace App\EventListener;

use App\Event\CommentReadEvent;
use App\Service\TimesheetCommentService;
use JetBrains\PhpStorm\NoReturn;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

readonly class CommentReadListener
{
    public function __construct(
        private TimesheetCommentService $commentService,
    ) {
    }

    #[NoReturn] #[AsEventListener]
    public function onCommentRead(CommentReadEvent $event): void
    {
        $comments = $event->getComments();
        $this->commentService->turnCommentRead(comments: $comments, flush: true);
    }
}
