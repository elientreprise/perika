<?php

namespace App\Enum\Entity;

enum CommentStatusEnum: string
{
    case NEW = 'new';
    case READ = 'read';
}
