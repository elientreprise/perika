<?php

namespace App\Entity;

use App\Entity\ValueObject\Location;
use App\Enum\Entity\WeekDayEnum;
use App\Repository\TimesheetWorkDayRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TimesheetWorkDayRepository::class)]
class TimesheetWorkDay
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'workDays')]
    #[ORM\JoinColumn(nullable: false)]
    private Timesheet $timesheet;

    #[ORM\Column(enumType: WeekDayEnum::class)]
    private WeekDayEnum $day;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $projectTime = null;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private ?bool $isMinDailyRestMet = null;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private ?bool $isWorkShiftValid = null;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private ?bool $workedMoreThanHalfDay = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $lunchBreak = null;

    #[ORM\Embedded(class: Location::class)]
    private ?Location $location = null;

    public function __construct()
    {
        $this->location = new Location();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTimesheet(): Timesheet
    {
        return $this->timesheet;
    }

    public function setTimesheet(Timesheet $timesheet): self
    {
        $this->timesheet = $timesheet;

        return $this;
    }

    public function getDay(): WeekDayEnum
    {
        return $this->day;
    }

    public function setDay(WeekDayEnum $day): self
    {
        $this->day = $day;

        return $this;
    }

    public function getProjectTime(): ?int
    {
        return $this->projectTime;
    }

    public function setProjectTime(?int $projectTime): self
    {
        $this->projectTime = $projectTime;

        return $this;
    }

    public function isMinDailyRestMet(): ?bool
    {
        return $this->isMinDailyRestMet;
    }

    public function setIsMinDailyRestMet(?bool $value): self
    {
        $this->isMinDailyRestMet = $value;

        return $this;
    }

    public function isWorkShiftValid(): ?bool
    {
        return $this->isWorkShiftValid;
    }

    public function setIsWorkShiftValid(?bool $value): self
    {
        $this->isWorkShiftValid = $value;

        return $this;
    }

    public function isWorkedMoreThanHalfDay(): ?bool
    {
        return $this->workedMoreThanHalfDay;
    }

    public function setWorkedMoreThanHalfDay(?bool $value): self
    {
        $this->workedMoreThanHalfDay = $value;

        return $this;
    }

    public function getLunchBreak(): ?int
    {
        return $this->lunchBreak;
    }

    public function setLunchBreak(?int $lunchBreak): self
    {
        $this->lunchBreak = $lunchBreak;

        return $this;
    }

    public function getLocation(): ?Location
    {
        return $this->location;
    }

    public function setLocation(?Location $location): self
    {
        $this->location = $location;

        return $this;
    }
}
