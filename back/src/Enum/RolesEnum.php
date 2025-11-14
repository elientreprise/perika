<?php

namespace App\Enum;

enum RolesEnum: string
{
    case ROLE_RH = 'ROLE_RH';
    case ROLE_MANAGER = 'ROLE_MANAGER';
    case ROLE_EMPLOYEE = 'ROLE_EMPLOYEE';
    case ROLE_ADMIN = 'ROLE_ADMIN';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
