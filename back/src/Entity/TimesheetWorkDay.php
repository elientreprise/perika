<?php

namespace App\Entity;

use App\Entity\Trait\TimestampableTrait;
use App\Entity\ValueObject\Location;
use App\Enum\Entity\WeekDayEnum;
use App\Repository\TimesheetWorkDayRepository;
use App\Validator\ValidWorkdays;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: TimesheetWorkDayRepository::class)]
#[ValidWorkdays(groups: ['create'])]
class TimesheetWorkDay
{
    use TimestampableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\ManyToOne(inversedBy: 'workDays', fetch: 'EAGER')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotBlank(groups: ['create'])]
    private Timesheet $timesheet;

    #[ORM\Column(enumType: WeekDayEnum::class)]
    #[Groups(['timesheet:read', 'timesheet:write'])]
    #[Assert\NotBlank(groups: ['create'])]
    private WeekDayEnum $day;

    #[ORM\Column(options: ['default' => 0])]
    #[Groups(['timesheet:read', 'timesheet:write'])]
    private float $projectTime = 0;

    #[ORM\Column(type: 'boolean', nullable: true)]
    #[Groups(['timesheet:read', 'timesheet:write'])]
    private ?bool $isMinDailyRestMet = null;

    #[ORM\Column(type: 'boolean', nullable: true)]
    #[Groups(['timesheet:read', 'timesheet:write'])]
    private ?bool $isWorkShiftValid = null;

    #[ORM\Column(type: 'boolean', nullable: true)]
    #[Groups(['timesheet:read', 'timesheet:write'])]
    private ?bool $workedMoreThanHalfDay = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    #[Groups(['timesheet:read', 'timesheet:write'])]
    private ?int $lunchBreak = null;

    #[ORM\Embedded(class: Location::class)]
    #[Groups(['timesheet:read', 'timesheet:write'])]
    private ?Location $location;

    public function __construct()
    {
        $this->location = new Location();
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
    }

    public function getId(): int
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

    public function getProjectTime(): ?float
    {
        return $this->projectTime;
    }

    public function setProjectTime(float $value): self
    {
        $this->projectTime = $value;

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

    public function isProjectTimeOverZero(): bool
    {
        return $this->projectTime > 0.0;
    }

    public function isZeroHourProjectTime(): bool
    {
        return 0.0 === $this->projectTime;
    }
}
