<?php

namespace App\Service;

use App\Entity\Timesheet;
use App\Entity\User;
use App\Repository\TimesheetRepository;

readonly class TimesheetService
{
    public function __construct(private TimesheetRepository $timesheetRepository)
    {
    }

    public function getById(int $id): ?Timesheet
    {
        return $this->timesheetRepository->findOneBy(['id' => $id]);
    }

    public function getByEmployee(string $employeeUuid, string $uuid): ?Timesheet
    {
        return $this->timesheetRepository->findOneByEmployee($employeeUuid, $uuid);
    }

    public function getOverlapTimesheet(User $employee, \DateTimeInterface $startPeriod, \DateTimeInterface $endPeriod): bool
    {
        return $this->timesheetRepository->hasOverlap($employee, $startPeriod, $endPeriod);
    }

    public function timesheetPeriodCalculator(\DateTimeInterface $endPeriod): array
    {
        $dayOfWeek = (int) $endPeriod->format('w');
        $start = (clone $endPeriod)->modify("-{$dayOfWeek} days");
        $end = (clone $start)->modify('+6 days');

        return [$start, $end];
    }
}
