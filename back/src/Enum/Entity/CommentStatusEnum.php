<?php

namespace App\Enum\Entity;

enum CommentStatusEnum: string
{
    case NEW = 'new';
    case READ = 'read';

    // todo: utiliser symfony translate
    public static function translate(CommentStatusEnum $enum): string
    {
        return match (true) {
            self::NEW === $enum => 'Nouveau.',
            self::READ === $enum => 'Lu.',
            default => '',
        };
    }
}
