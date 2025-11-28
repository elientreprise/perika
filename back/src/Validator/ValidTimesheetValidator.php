<?php

namespace App\Validator;

use App\Entity\Timesheet;
use App\Entity\User;
use App\Enum\ResponseMessage\ErrorMessageEnum;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ValidTimesheetValidator extends ConstraintValidator
{
    #[\Override]
    public function validate($value, Constraint $constraint): void
    {
        /** @var Timesheet $value */
        if (!$value || !$constraint instanceof ValidTimesheet) {
            return;
        }

        $totalTime = $value->computeTotalTime();

        if (User::MAX_TOTAL_TIME_PROJECT > $totalTime) {
            $this->context
                ->buildViolation(ErrorMessageEnum::TS_NOT_ENOUGH_TOTAL_HOURS->value)
                ->atPath('workDays')
                ->addViolation();
        }

        if (User::MAX_TOTAL_TIME_PROJECT < $totalTime) {
            $this->context
                ->buildViolation(ErrorMessageEnum::TS_TOO_MUCH_TOTAL_HOURS->value)
                ->atPath('workDays')
                ->addViolation();
        }
    }
}
