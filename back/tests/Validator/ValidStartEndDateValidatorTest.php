<?php

namespace App\Tests\Validator;

use App\Entity\Timesheet;
use App\Enum\ResponseMessage\ErrorMessageEnum;
use App\Validator\ValidStartEndDate;
use App\Validator\ValidStartEndDateValidator;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

class ValidStartEndDateValidatorTest extends ConstraintValidatorTestCase
{
    protected function createValidator(): ValidStartEndDateValidator
    {
        return new ValidStartEndDateValidator();
    }

    public function testValidDates(): void
    {
        $timesheet = (new Timesheet())
            ->setStartPeriod(new \DateTimeImmutable('2024-01-01'))
            ->setEndPeriod(new \DateTimeImmutable('2024-01-02'));

        $constraint = new ValidStartEndDate();

        $this->validator->validate($timesheet, $constraint);

        $this->assertNoViolation();
    }

    public function testStartGreaterThanEnd(): void
    {
        $timesheet = (new Timesheet())
            ->setStartPeriod(new \DateTimeImmutable('2024-01-10'))
            ->setEndPeriod(new \DateTimeImmutable('2024-01-05'));

        $constraint = new ValidStartEndDate();

        $this->validator->validate($timesheet, $constraint);

        $this->buildViolation(ErrorMessageEnum::TS_START_PERIOD_LESS_THAN_END_PERIOD->value)
            ->atPath('property.path.endPeriod')
            ->buildNextViolation(ErrorMessageEnum::TS_END_PERIOD_GREATER_THAN_START_PERIOD->value)
            ->atPath('property.path.endPeriod')
            ->assertRaised();
    }

    public function testEndLessOrEqualStart(): void
    {
        $timesheet = (new Timesheet())
            ->setStartPeriod(new \DateTimeImmutable('2024-01-10'))
            ->setEndPeriod(new \DateTimeImmutable('2024-01-10'));

        $constraint = new ValidStartEndDate();

        $this->validator->validate($timesheet, $constraint);

        $this->buildViolation(ErrorMessageEnum::TS_START_PERIOD_LESS_THAN_END_PERIOD->value)
            ->atPath('property.path.endPeriod')
            ->buildNextViolation(ErrorMessageEnum::TS_END_PERIOD_GREATER_THAN_START_PERIOD->value)
            ->atPath('property.path.endPeriod')
            ->assertRaised();
    }

    public function testIgnoreNonTimesheet(): void
    {
        $constraint = new ValidStartEndDate();

        $this->validator->validate(null, $constraint);

        $this->assertNoViolation();
    }
}
