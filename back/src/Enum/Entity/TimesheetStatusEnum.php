<?php

namespace App\Enum\Entity;

enum TimesheetStatusEnum: string
{
    case PENDING = 'pending';
    case DRAFT = 'draft';
    case NEED_REVIEW = 'need_review';
    case SUBMITTED = 'submitted';
    case VALID = "valid";
}
