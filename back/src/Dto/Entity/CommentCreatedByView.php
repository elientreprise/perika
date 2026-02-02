<?php

namespace App\Dto\Entity;

class CommentCreatedByView
{
    public function __construct(
        public string $uuid,
        public string $fullName,
    ) {
    }
}
