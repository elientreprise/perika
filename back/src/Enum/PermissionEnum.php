<?php

namespace App\Enum;

enum PermissionEnum: string
{
    case CAN_EDIT_EMPLOYEE = 'can_edit_employee';
    case CAN_VIEW_EMPLOYEE = 'can_view_employee';
    case CAN_ADD_SUBORDINATES = 'can_add_subordinates';
    case CAN_CREATE_TIMESHEET = 'can_create_timesheet';
    case CAN_VIEW_TIMESHEET = 'can_view_timesheet';
    case CAN_VIEW_TIMESHEET_COLLECTION = 'can_view_timesheet_collection';
    case CAN_EDIT_TIMESHEET = 'can_edit_timesheet';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
