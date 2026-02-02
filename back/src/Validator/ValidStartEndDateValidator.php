<?php

namespace App\Validator;

use App\Entity\Timesheet;
use App\Enum\ResponseMessage\ErrorMessageEnum;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ValidStartEndDateValidator extends ConstraintValidator
{
    #[\Override]
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof ValidStartEndDate
            || !$value instanceof Timesheet
        ) {
            return;
        }

        $start = $value->getStartPeriod();
        $end = $value->getEndPeriod();

        if (!$start || !$end) {
            return;
        }

        if ($start >= $end) {
            $this->context
                ->buildViolation(ErrorMessageEnum::TS_START_PERIOD_LESS_THAN_END_PERIOD->value)
                ->atPath('endPeriod')
                ->addViolation();
        }

        if ($end <= $start) {
            $this->context
                ->buildViolation(ErrorMessageEnum::TS_END_PERIOD_GREATER_THAN_START_PERIOD->value)
                ->atPath('endPeriod')
                ->addViolation();
        }
    }
}
