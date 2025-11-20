<?php

namespace App\Enum\ResponseMessage;

enum ErrorMessageEnum: string
{
    // TIMESHEET
    case TS_NOT_ENOUGH_TOTAL_HOURS_ = 'Le total d\'heure de la semaine n\'est suffisant avec celui attendu.';
    case TS_TOO_MUCH_TOTAL_HOURS_ = 'Le total d\'heure de la semaine est supérieur à celui attendu.';
    case TS_MINIMUM_DAILY_REST_MISSING = 'Le temps de repos minimum est manquant pour un ou plusieurs jours de la semaine.';
    case TS_WORK_SHIFT_MISSING = 'Le temps de travail effectif est manquant pour un ou plusieurs jours de la semaine.';
    case TS_WORKED_MORE_THAN_HALF_DAY_MISSING = 'Le temps de travail supérieur à une demi-journée est manquant pour un ou plusieurs jours de la semaine.';
    case TS_LUNCH_BREAK_MISSING = 'La durée de la pause du déjeuner est manquante pour un ou plusieurs jours de la semaine.';
    case TS_LOCATION_MISSING = 'La localisation est manquante pour un ou plusieurs jours de la semaine.';
    case TS_LOCATION_UNEXPECTED_VALUE = 'La localisation est rempli pour un ou plusieurs jours de la semaine non travaillé';
}
