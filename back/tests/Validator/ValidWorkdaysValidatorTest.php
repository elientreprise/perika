<?php

namespace App\Tests\Validator;

use App\Entity\TimesheetWorkDay;
use App\Entity\ValueObject\Location;
use App\Enum\ResponseMessage\ErrorMessageEnum;
use App\Validator\ValidWorkdays;
use App\Validator\ValidWorkdaysValidator;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

class ValidWorkdaysValidatorTest extends ConstraintValidatorTestCase
{
    protected function createValidator(): ValidWorkdaysValidator
    {
        return new ValidWorkdaysValidator();
    }

    public function testLocationMissingOnWorkedDay(): void
    {
        $day = (new TimesheetWorkDay())
            ->setProjectTime(7.40)
            ->setIsMinDailyRestMet(true)
            ->setIsWorkShiftValid(true)
            ->setWorkedMoreThanHalfDay(true)
            ->setLunchBreak(1)
            ->setLocation(null);

        $this->validator->validate($day, new ValidWorkdays());

        $this->buildViolation(ErrorMessageEnum::TS_LOCATION_MISSING->value)
            ->atPath('property.path.location')
            ->assertRaised();
    }

    public function testLunchBreakUnexpectedOnNotWorkedDay(): void
    {
        $day = (new TimesheetWorkDay())
            ->setProjectTime(0)
            ->setLunchBreak(1);

        $this->validator->validate($day, new ValidWorkdays());

        $this->buildViolation(ErrorMessageEnum::TS_LUNCH_BREAK_UNEXPECTED_VALUE->value)
            ->atPath('property.path.lunchBreak')
            ->assertRaised();
    }

    public function testValidWorkedDay(): void
    {
        $location = (new Location())
            ->setAm('string')
            ->setPm('string');

        $day = (new TimesheetWorkDay())
            ->setProjectTime(7.40)
            ->setIsMinDailyRestMet(true)
            ->setIsWorkShiftValid(true)
            ->setWorkedMoreThanHalfDay(true)
            ->setLunchBreak(1)
            ->setLocation($location);

        $this->validator->validate($day, new ValidWorkdays());

        $this->assertNoViolation();
    }

    public function testValidNotWorkedDay(): void
    {
        $day = (new TimesheetWorkDay())
            ->setProjectTime(0)
            ->setIsMinDailyRestMet(null)
            ->setIsWorkShiftValid(null)
            ->setWorkedMoreThanHalfDay(null)
            ->setLunchBreak(null)
            ->setLocation(null);

        $this->validator->validate($day, new ValidWorkdays());

        $this->assertNoViolation();
    }
}
