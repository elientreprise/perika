<?php

namespace App\Enum\ResponseMessage;

enum SuccessMessageEnum: string
{
    // TIMESHEET
    case TS_SUBMITTED = 'Feuille de temps soumise avec succès.';
    case TS_COMMENT_ADDED = 'Commentaire ajouté avec succès.';
    case TS_VALIDATED = 'Feuille de temps validée avec succès.';
}
