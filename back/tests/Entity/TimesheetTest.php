<?php

namespace App\Tests\Entity;

use App\Entity\Timesheet;
use App\Entity\TimesheetWorkDay;
use App\Entity\User;
use App\Enum\Entity\WeekDayEnum;
use PHPUnit\Framework\TestCase;

class TimesheetTest extends TestCase
{
    public function testComputeTotalTime(): void
    {
        $timesheet = new Timesheet();
        $timesheet->addWorkDay((new TimesheetWorkDay())
            ->setDay(WeekDayEnum::Monday)
            ->setProjectTime(8)
        );
        $timesheet->addWorkDay((new TimesheetWorkDay())
            ->setDay(WeekDayEnum::Sunday)
            ->setProjectTime(8)
        );

        $this->assertEquals(16, $timesheet->computeTotalTime());
    }

    public function testIsOwner(): void
    {
        $user = (new User())
            ->setEmail('test@test.com')
        ;
        $timesheet = new Timesheet();
        $timesheet->setEmployee($user);

        $this->assertTrue($timesheet->isOwner($user));
    }

    public function testIsNotOwner(): void
    {
        $owner = (new User())
            ->setEmail('owner@test.com')
        ;

        $user = (new User())
            ->setEmail('test@test.com')
        ;

        $timesheet = new Timesheet();
        $timesheet->setEmployee($owner);

        $this->assertFalse($timesheet->isOwner($user));
    }
}
