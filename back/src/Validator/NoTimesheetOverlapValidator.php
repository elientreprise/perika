<?php

namespace App\Validator;

use App\Entity\Timesheet;
use App\Enum\ResponseMessage\ErrorMessageEnum;
use App\Service\TimesheetService;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class NoTimesheetOverlapValidator extends ConstraintValidator
{
    public function __construct(
        private readonly TimesheetService $timesheetService,
    ) {
    }

    public function validate($value, Constraint $constraint): void
    {
        if (
            !$constraint instanceof NoTimesheetOverlap
            || !$value instanceof Timesheet
        ) {
            return;
        }

        $employee = $value->getEmployee();
        [$start, $end] = $this->timesheetService->timesheetPeriodCalculator($value->getEndPeriod());

        $value->setStartPeriod($start);
        $value->setEndPeriod($end);

        if ($this->timesheetService->getOverlapTimesheet($employee, $start, $end)) {
            $this->context
                ->buildViolation(ErrorMessageEnum::TS_ALREADY_EXIST->value)
                ->atPath('endPeriod')
                ->addViolation();
        }
    }
}
