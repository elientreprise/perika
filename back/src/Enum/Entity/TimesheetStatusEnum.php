<?php

namespace App\Enum\Entity;

enum TimesheetStatusEnum: string
{
    case PENDING = 'pending';
    case SUBMITTED = 'submitted';
    case DRAFT = 'draft';
    case DELETED = 'deleted';
    case NEED_EDIT = 'need_edit';
    case VALID = 'valid';

    // todo: utiliser symfony translate
    public static function translate(TimesheetStatusEnum $enum): string
    {
        return match (true) {
            self::PENDING === $enum => 'En attente de révision.',
            self::DRAFT === $enum => 'Brouillon.',
            self::SUBMITTED === $enum => 'Soumise.',
            self::DELETED === $enum => 'Supprimée.',
            self::NEED_EDIT === $enum => 'Besoin de modification.',
            self::VALID === $enum => 'Validée.',
            default => '',
        };
    }
}
