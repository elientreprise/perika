<?php

namespace App\Factory;

use App\Entity\TimesheetWorkDay;
use App\Entity\ValueObject\Location;
use App\Enum\Entity\WeekDayEnum;
use Zenstruck\Foundry\Persistence\PersistentObjectFactory;

final class TimesheetWorkDayFactory extends PersistentObjectFactory
{
    public static function class(): string
    {
        return TimesheetWorkDay::class;
    }

    protected function defaults(): array
    {
        $location = (new Location())
            ->setAm(self::faker()->city())
            ->setPm(self::faker()->city())
        ;

        return [
            'day' => self::faker()->randomElement(WeekDayEnum::cases()),
            'projectTime' => self::faker()->numberBetween(0, 8),
            'isMinDailyRestMet' => self::faker()->boolean(),
            'isWorkShiftValid' => self::faker()->boolean(),
            'workedMoreThanHalfDay' => self::faker()->boolean(),
            'lunchBreak' => self::faker()->numberBetween(0, 2),
            'location' => $location,
            'timesheet' => TimesheetFactory::new(),
        ];
    }
}
