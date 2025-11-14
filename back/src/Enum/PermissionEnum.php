<?php

namespace App\Enum;

enum PermissionEnum: string
{
    case CAN_EDIT_EMPLOYEE = 'can_edit_employee';
    case CAN_VIEW_EMPLOYEE = 'can_view_employee';
    case CAN_ADD_SUBORDINATES = 'can_add_subordinates';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
