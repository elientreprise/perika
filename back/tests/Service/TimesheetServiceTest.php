<?php

namespace App\Tests\Service;

use App\Entity\Timesheet;
use App\Entity\User;
use App\Repository\TimesheetRepository;
use App\Service\TimesheetService;
use PHPUnit\Framework\TestCase;

class TimesheetServiceTest extends TestCase
{
    private TimesheetRepository $repository;
    private TimesheetService $service;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(TimesheetRepository::class);
        $this->service = new TimesheetService($this->repository);
    }

    public function testGetByIdReturnsTimesheet(): void
    {
        $timesheet = new Timesheet();

        $this->repository
            ->expects($this->once())
            ->method('findOneBy')
            ->with(['id' => 42])
            ->willReturn($timesheet);

        $result = $this->service->getById(42);

        $this->assertSame($timesheet, $result);
    }

    public function testGetByIdReturnsNull(): void
    {
        $this->repository
            ->expects($this->once())
            ->method('findOneBy')
            ->with(['id' => 99])
            ->willReturn(null);

        $result = $this->service->getById(99);

        $this->assertNull($result);
    }

    public function testGetOverlapTimesheetTrue(): void
    {
        $employee = new User();
        $start = new \DateTimeImmutable('2024-01-01');
        $end = new \DateTimeImmutable('2024-01-07');

        $this->repository
            ->expects($this->once())
            ->method('hasOverlap')
            ->with($employee, $start, $end)
            ->willReturn(true);

        $result = $this->service->getOverlapTimesheet($employee, $start, $end);

        $this->assertTrue($result);
    }

    public function testGetOverlapTimesheetFalse(): void
    {
        $employee = new User();
        $start = new \DateTimeImmutable('2024-01-01');
        $end = new \DateTimeImmutable('2024-01-07');

        $this->repository
            ->expects($this->once())
            ->method('hasOverlap')
            ->with($employee, $start, $end)
            ->willReturn(false);

        $result = $this->service->getOverlapTimesheet($employee, $start, $end);

        $this->assertFalse($result);
    }

    public function testTimesheetPeriodCalculator(): void
    {
        $end = new \DateTimeImmutable('2024-01-10');
        [$start, $calculatedEnd] = $this->service->timesheetPeriodCalculator($end);

        $this->assertEquals('2024-01-07', $start->format('Y-m-d'));
        $this->assertEquals('2024-01-13', $calculatedEnd->format('Y-m-d'));
    }
}
