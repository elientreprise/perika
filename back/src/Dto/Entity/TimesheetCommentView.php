<?php

namespace App\Dto\Entity;

class TimesheetCommentView
{
    public function __construct(
        public string $uuid,
        public string $comment,
        public CommentCreatedByView $createdBy,
        public string $createdAt,
    ) {
    }
}
