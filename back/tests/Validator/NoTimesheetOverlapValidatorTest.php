<?php

namespace App\Tests\Validator;

use App\Entity\Timesheet;
use App\Entity\User;
use App\Enum\ResponseMessage\ErrorMessageEnum;
use App\Service\TimesheetService;
use App\Validator\NoTimesheetOverlap;
use App\Validator\NoTimesheetOverlapValidator;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

class NoTimesheetOverlapValidatorTest extends ConstraintValidatorTestCase
{
    private MockObject $timesheetService;

    protected function createValidator(): NoTimesheetOverlapValidator
    {
        $this->timesheetService = $this->createMock(TimesheetService::class);

        return new NoTimesheetOverlapValidator($this->timesheetService);
    }

    public function testNoOverlap(): void
    {
        $employee = new User();

        $timesheet = (new Timesheet())
            ->setEmployee($employee)
            ->setEndPeriod(new \DateTimeImmutable('2024-01-10'));

        $this->timesheetService
            ->method('timesheetPeriodCalculator')
            ->willReturn([
                new \DateTimeImmutable('2024-01-01'),
                new \DateTimeImmutable('2024-01-07'),
            ]);

        $this->timesheetService
            ->method('getOverlapTimesheet')
            ->willReturn(false);

        $constraint = new NoTimesheetOverlap();

        $this->validator->validate($timesheet, $constraint);

        $this->assertNoViolation();
    }

    public function testOverlapDetected(): void
    {
        $employee = new User();

        $timesheet = (new Timesheet())
            ->setEmployee($employee)
            ->setEndPeriod(new \DateTimeImmutable('2024-01-07'));

        $this->timesheetService
            ->method('timesheetPeriodCalculator')
            ->willReturn([
                new \DateTimeImmutable('2024-01-01'),
                new \DateTimeImmutable('2024-01-07'),
            ]);

        $this->timesheetService
            ->method('getOverlapTimesheet')
            ->willReturn(true);

        $constraint = new NoTimesheetOverlap();

        $this->validator->validate($timesheet, $constraint);

        $this->buildViolation(ErrorMessageEnum::TS_ALREADY_EXIST->value)
            ->atPath('property.path.endPeriod')
            ->assertRaised();
    }

    public function testIgnoreInvalidValue(): void
    {
        $constraint = new NoTimesheetOverlap();

        $this->validator->validate(new \stdClass(), $constraint);

        $this->assertNoViolation();
    }
}
