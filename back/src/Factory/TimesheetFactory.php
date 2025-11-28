<?php

namespace App\Factory;

use App\Entity\Timesheet;
use App\Enum\Entity\WeekDayEnum;
use Zenstruck\Foundry\Persistence\PersistentObjectFactory;

/**
 * @extends PersistentObjectFactory<Timesheet>
 */
final class TimesheetFactory extends PersistentObjectFactory
{
    #[\Override]
    public static function class(): string
    {
        return Timesheet::class;
    }

    protected function defaults(): array|callable
    {
        $monday = (new \DateTimeImmutable('monday this week'));
        $sunday = $monday->modify('+6 days');

        return [
            'employee' => UserFactory::new(),
            'startPeriod' => $monday,
            'endPeriod' => $sunday,
        ];
    }

    public function withWorkDays(): self
    {
        return $this->afterPersist(function (Timesheet $timesheet): void {
            foreach (WeekDayEnum::cases() as $day) {
                TimesheetWorkDayFactory::new([
                    'timesheet' => $timesheet,
                    'day' => $day,
                ])->create();
            }
        });
    }
}
