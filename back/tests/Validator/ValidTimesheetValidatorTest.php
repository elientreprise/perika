<?php

namespace App\Tests\Validator;

use App\Entity\Timesheet;
use App\Entity\User;
use App\Enum\ResponseMessage\ErrorMessageEnum;
use App\Validator\ValidTimesheet;
use App\Validator\ValidTimesheetValidator;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

class ValidTimesheetValidatorTest extends ConstraintValidatorTestCase
{
    protected function createValidator(): ValidTimesheetValidator
    {
        return new ValidTimesheetValidator();
    }

    public function testTotalTimeTooLow(): void
    {
        $timesheet = $this->createMock(Timesheet::class);
        $timesheet->method('computeTotalTime')
            ->willReturn(User::MAX_TOTAL_TIME_PROJECT - 5);

        $this->validator->validate($timesheet, new ValidTimesheet());

        $this->buildViolation(ErrorMessageEnum::TS_NOT_ENOUGH_TOTAL_HOURS->value)
            ->atPath('property.path.workDays')
            ->assertRaised();
    }

    public function testTotalTimeTooHigh(): void
    {
        $timesheet = $this->createMock(Timesheet::class);
        $timesheet->method('computeTotalTime')
            ->willReturn(User::MAX_TOTAL_TIME_PROJECT + 5);

        $this->validator->validate($timesheet, new ValidTimesheet());

        $this->buildViolation(ErrorMessageEnum::TS_TOO_MUCH_TOTAL_HOURS->value)
            ->atPath('property.path.workDays')
            ->assertRaised();
    }

    public function testTotalTimeExact(): void
    {
        $timesheet = $this->createMock(Timesheet::class);
        $timesheet->method('computeTotalTime')
            ->willReturn(User::MAX_TOTAL_TIME_PROJECT);

        $this->validator->validate($timesheet, new ValidTimesheet());

        $this->assertNoViolation();
    }

    public function testValidatorIgnoresInvalidValue(): void
    {
        $this->validator->validate(null, new ValidTimesheet());

        $this->assertNoViolation();
    }
}
