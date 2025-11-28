<?php

namespace App\Validator;

use App\Entity\TimesheetWorkDay;
use App\Enum\ResponseMessage\ErrorMessageEnum;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ValidWorkdaysValidator extends ConstraintValidator
{
    #[\Override]
    public function validate($value, Constraint $constraint): void
    {
        /** @var TimesheetWorkDay $value */
        if (!$value || !$constraint instanceof ValidWorkdays) {
            return;
        }

        $this->minimumDailyRestNotNullOnNotWorkedDay($value, $constraint);

        $this->minimumDailyRestNullOnWorkedDay($value, $constraint);

        $this->workedMoreThanHalfDayNullOnWorkedDay($value, $constraint);

        $this->workedMoreThanHalfDayNotNullOnNotWorkedDay($value, $constraint);

        $this->locationNotNullOnNotWorkedDay($value, $constraint);

        $this->locationNullOnWorkedDay($value, $constraint);

        $this->lunchBreakNotNullOnNotWorkedDay($value, $constraint);

        $this->lunchBreakNullOnWorkedDay($value, $constraint);

        $this->workShiftValidNotNullOnNotWorkedDay($value, $constraint);

        $this->workShiftValidNullOnWorkedDay($value, $constraint);
    }

    public function locationNullOnWorkedDay(TimesheetWorkDay $value, Constraint $constraint): void
    {
        if ($value->isProjectTimeOverZero()) {
            if (null === $value->getLocation()) {
                $this->context
                    ->buildViolation(ErrorMessageEnum::TS_LOCATION_MISSING->value)
                    ->atPath('location')
                    ->addViolation();
            }

            if ($value->getLocation()) {
                if (null === $value->getLocation()->getAm()) {
                    $this->context
                        ->buildViolation(ErrorMessageEnum::TS_LOCATION_MISSING->value)
                        ->atPath('location.am')
                        ->addViolation();
                }

                if (null === $value->getLocation()->getPm()) {
                    $this->context
                        ->buildViolation(ErrorMessageEnum::TS_LOCATION_MISSING->value)
                        ->atPath('location.pm')
                        ->addViolation();
                }
            }
        }
    }

    public function minimumDailyRestNullOnWorkedDay(TimesheetWorkDay $value, Constraint $constraint): void
    {
        if ($value->isProjectTimeOverZero() && null === $value->isMinDailyRestMet()) {
            $this->context
                ->buildViolation(ErrorMessageEnum::TS_MINIMUM_DAILY_REST_MISSING->value)
                ->atPath('isMinDailyRestMet')
                ->addViolation();
        }
    }

    public function workShiftValidNullOnWorkedDay(TimesheetWorkDay $value, Constraint $constraint): void
    {
        if ($value->isProjectTimeOverZero() && null === $value->isWorkShiftValid()) {
            $this->context
                ->buildViolation(ErrorMessageEnum::TS_WORK_SHIFT_MISSING->value)
                ->atPath('isWorkShiftValid')
                ->addViolation();
        }
    }

    public function workedMoreThanHalfDayNullOnWorkedDay(TimesheetWorkDay $value, Constraint $constraint): void
    {
        if ($value->isProjectTimeOverZero() && null === $value->isWorkedMoreThanHalfDay()) {
            $this->context
                ->buildViolation(ErrorMessageEnum::TS_WORKED_MORE_THAN_HALF_DAY_MISSING->value)
                ->atPath('workedMoreThanHalfDay')
                ->addViolation();
        }
    }

    public function lunchBreakNullOnWorkedDay(TimesheetWorkDay $value, Constraint $constraint): void
    {
        if ($value->isProjectTimeOverZero() && null === $value->getLunchBreak()) {
            $this->context
                ->buildViolation(ErrorMessageEnum::TS_LUNCH_BREAK_MISSING->value)
                ->atPath('lunchBreak')
                ->addViolation();
        }
    }

    public function lunchBreakNotNullOnNotWorkedDay(TimesheetWorkDay $value, Constraint $constraint): void
    {
        if ($value->isZeroHourProjectTime() && $value->getLunchBreak()) {
            $this->context
                ->buildViolation(ErrorMessageEnum::TS_LUNCH_BREAK_UNEXPECTED_VALUE->value)
                ->atPath('lunchBreak')
                ->addViolation();
        }
    }

    public function locationNotNullOnNotWorkedDay(TimesheetWorkDay $value, Constraint $constraint): void
    {
        if ($value->isZeroHourProjectTime() && $value->getLocation()) {
            if ($value->getLocation()->getAm()) {
                $this->context
                    ->buildViolation(ErrorMessageEnum::TS_LOCATION_UNEXPECTED_VALUE->value)
                    ->atPath('location.am')
                    ->addViolation();
            }

            if ($value->getLocation()->getPm()) {
                $this->context
                    ->buildViolation(ErrorMessageEnum::TS_LOCATION_UNEXPECTED_VALUE->value)
                    ->atPath('location.pm')
                    ->addViolation();
            }
        }
    }

    public function minimumDailyRestNotNullOnNotWorkedDay(TimesheetWorkDay $value, Constraint $constraint): void
    {
        if ($value->isZeroHourProjectTime() && $value->isMinDailyRestMet()) {
            $this->context
                ->buildViolation(ErrorMessageEnum::TS_MINIMUM_DAILY_REST_UNEXPECTED_VALUE->value)
                ->atPath('isMinDailyRestMet')
                ->addViolation();
        }
    }

    public function workShiftValidNotNullOnNotWorkedDay(TimesheetWorkDay $value, Constraint $constraint): void
    {
        if ($value->isZeroHourProjectTime() && $value->isWorkShiftValid()) {
            $this->context
                ->buildViolation(ErrorMessageEnum::TS_WORK_SHIFT_UNEXPECTED_VALUE->value)
                ->atPath('isWorkShiftValid')
                ->addViolation();
        }
    }

    public function workedMoreThanHalfDayNotNullOnNotWorkedDay(TimesheetWorkDay $value, Constraint $constraint): void
    {
        if ($value->isZeroHourProjectTime() && $value->isWorkedMoreThanHalfDay()) {
            $this->context
                ->buildViolation(ErrorMessageEnum::TS_WORKED_MORE_THAN_HALF_DAY_UNEXPECTED_VALUE->value)
                ->atPath('workedMoreThanHalfDay')
                ->addViolation();
        }
    }
}
