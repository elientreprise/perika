<?php

namespace App\Enum\Entity;

enum CoreUserRoleEnum: string
{
    case RH = 'ROLE_RH';
    case MANAGER = 'ROLE_MANAGER';

    case ADMIN = 'ROLE_ADMIN';
}
