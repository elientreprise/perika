<?php

namespace App\Enum\ResponseMessage;

enum ErrorMessageEnum: string
{
    // TIMESHEET
    case TS_NOT_ENOUGH_TOTAL_HOURS = 'Le total d\'heure de la semaine est inférieur à celui attendu.';
    case TS_TOO_MUCH_TOTAL_HOURS = 'Le total d\'heure de la semaine est supérieur à celui attendu.';
    case TS_MINIMUM_DAILY_REST_MISSING = 'Le temps de repos minimum est manquant pour un ou plusieurs jours de la semaine.';
    case TS_WORK_SHIFT_MISSING = 'Le temps de travail effectif est manquant pour un ou plusieurs jours de la semaine.';
    case TS_WORKED_MORE_THAN_HALF_DAY_MISSING = 'Le temps de travail supérieur à une demi-journée est manquant pour un ou plusieurs jours de la semaine.';
    case TS_LUNCH_BREAK_MISSING = 'La durée de la pause du déjeuner est manquante pour un ou plusieurs jours de la semaine.';
    case TS_LOCATION_MISSING = 'La localisation est manquante pour un ou plusieurs jours de la semaine.';
    case TS_LOCATION_UNEXPECTED_VALUE = 'La localisation est rempli pour un ou plusieurs jours de la semaine non travaillé';
    case TS_MINIMUM_DAILY_REST_UNEXPECTED_VALUE = 'Le temps de repos minimum est rempli pour un ou plusieurs jours de la semaine non travaillé.';
    case TS_WORK_SHIFT_UNEXPECTED_VALUE = 'Le temps de travail effectif est rempli pour un ou plusieurs jours de la semaine non travaillé.';
    case TS_WORKED_MORE_THAN_HALF_DAY_UNEXPECTED_VALUE = 'Le temps de travail supérieur à une demi-journée est rempli pour un ou plusieurs jours de la semaine non travaillé.';
    case TS_LUNCH_BREAK_UNEXPECTED_VALUE = 'La durée de la pause du déjeuner est rempli pour un ou plusieurs jours de la semaine non travaillé.';
    case TS_CREATION_NOT_AUTHORIZED = 'Vous n\'êtes pas autorisé à créer une feuille de temps pour cet utilisateur.';
    case TS_ALREADY_EXIST = 'Une feuille de temps existe déjà pour cette période.';
    case TS_START_PERIOD_LESS_THAN_END_PERIOD = 'La date de début doit être inférieure à la date de fin.';
    case TS_END_PERIOD_GREATER_THAN_START_PERIOD = 'La date de fin doit être supérieur à la date de début.';
    case TS_EDITION_NOT_AUTHORIZED = 'Vous n\'êtes pas autorisé à modifier cette feuille de temps.';

    case TS_COMMENT_ADD_NOT_AUTHORIZED = 'Vous n\'êtes pas autorisé à ajouter un commentaire sur cette feuille de temps.';
}
