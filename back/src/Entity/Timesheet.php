<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\DateFilter;
use ApiPlatform\Doctrine\Orm\Filter\ExactFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Link;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\QueryParameter;
use App\Dto\Input\CalculatePeriodInput;
use App\Dto\Input\CheckTimesheetInput;
use App\Dto\Response\Timesheet\CheckTimesheetResponse;
use App\Dto\Response\Timesheet\TimesheetCalculatePeriodResponse;
use App\Enum\PermissionEnum;
use App\Filter\TimesheetSearchFilter;
use App\Repository\TimesheetRepository;
use App\State\Processor\CheckTimesheetExistsProcessor;
use App\State\Processor\TimesheetCalculatePeriodProcessor;
use App\State\Processor\TimesheetProcessor;
use App\State\Provider\EmployeeTimesheetProvider;
use App\Validator\NoTimesheetOverlap;
use App\Validator\ValidStartEndDate;
use App\Validator\ValidTimesheet;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: TimesheetRepository::class)]
#[ApiResource(
    operations: [
        new Post(
            uriTemplate: '/timesheets',
            normalizationContext: ['groups' => ['timesheet:read']],
            denormalizationContext: ['groups' => ['timesheet:write']],
            validationContext: ['groups' => ['Default', 'create']],
            output: false,
            processor: TimesheetProcessor::class
        ),
        new Post(
            uriTemplate: '/timesheets/check-exists',
            normalizationContext: ['groups' => ['timesheet:read']],
            denormalizationContext: ['groups' => ['timesheet:check-exists']],
            input: CheckTimesheetInput::class,
            output: CheckTimesheetResponse::class,
            processor: CheckTimesheetExistsProcessor::class,
        ),
        new Post(
            uriTemplate: '/timesheets/calculate-period',
            normalizationContext: ['groups' => ['timesheet:read']],
            denormalizationContext: ['groups' => ['timesheet:calculate-period']],
            input: CalculatePeriodInput::class,
            output: TimesheetCalculatePeriodResponse::class,
            processor: TimesheetCalculatePeriodProcessor::class
        ),
        new Get(
            uriTemplate: '/timesheets/{uuid}',
            normalizationContext: ['groups' => ['timesheet:read', 'timesheet:item:read']],
            security: "is_granted('".PermissionEnum::CAN_VIEW_TIMESHEET->value."', object)"
        ),
        new Get(
            uriTemplate: '/employees/{employeeUuid}/timesheets/{uuid}',
            uriVariables: [
                'employeeUuid' => 'employeeUuid',
                'uuid' => 'uuid'
            ],
            normalizationContext: ['groups' => ['timesheet:read', 'timesheet:item:read']],
            security: "is_granted('".PermissionEnum::CAN_VIEW_TIMESHEET->value."', object)",
            provider: EmployeeTimesheetProvider::class
        ),
        new GetCollection(
             uriTemplate: '/employees/{employeeUuid}/timesheets',
            uriVariables: [
                'employeeUuid' => new Link(
                    toProperty: 'employee',
                    fromClass: User::class,
                    security: "is_granted('".PermissionEnum::CAN_VIEW_TIMESHEET_COLLECTION->value."', employee)"
                ),
            ],
            normalizationContext: ['groups' => ['timesheet:read', 'timesheet:item:read']],
        ),
        new GetCollection(
            uriTemplate: '/timesheets',
            normalizationContext: ['groups' => ['timesheet:read', 'timesheet:item:read']],
            // todo: filter seulement par le manager
        )
    ],
    normalizationContext: ['groups' => ['timesheet:read']],
    denormalizationContext: ['groups' => ['timesheet:write', 'timesheet:calculate-period']],
    validationContext: ['groups' => 'Default', 'create', 'edit']
)]
#[ValidStartEndDate]
#[ValidTimesheet]
#[NoTimesheetOverlap]
#[ApiFilter(TimesheetSearchFilter::class, properties: [
    'uuid',
    'startPeriod',
    'endPeriod',
    'status',
])]
class Timesheet
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[ApiProperty(identifier: false)]
    private int $id;

    #[ORM\Column(type: 'uuid', unique: true)]
    #[ApiProperty(identifier: true)]
    #[Groups(['timesheet:read'])]
    private Uuid $uuid;

    #[ORM\ManyToOne(inversedBy: 'timesheets')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['timesheet:read', 'timesheet:write', 'timesheet:item:read'])]
    #[Assert\NotBlank()]
    private ?User $employee = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    #[Groups(['timesheet:item:read'])]
    #[Assert\NotBlank()]
    private ?\DateTimeImmutable $startPeriod = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    #[Groups(['timesheet:read', 'timesheet:write'])]
    #[Assert\NotBlank()]
    private ?\DateTimeImmutable $endPeriod = null;

    /** @var Collection<int, TimesheetWorkDay> */
    #[ORM\OneToMany(targetEntity: TimesheetWorkDay::class, mappedBy: 'timesheet', cascade: ['persist'], orphanRemoval: true)]
    #[Groups(['timesheet:read', 'timesheet:write'])]
    #[Assert\Valid]
    private Collection $workDays;

    #[ORM\Column(options: ['default' => 0])]
    #[Groups(['timesheet:item:read'])]
    private float $totalTime = 0;

    public function __construct()
    {
        $this->uuid = Uuid::v4();
        $this->workDays = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getEmployee(): ?User
    {
        return $this->employee;
    }

    public function setEmployee(?User $employee): static
    {
        $this->employee = $employee;

        return $this;
    }

    public function getStartPeriod(): ?\DateTimeImmutable
    {
        return $this->startPeriod;
    }

    public function setStartPeriod(\DateTimeImmutable $startPeriod): static
    {
        $this->startPeriod = $startPeriod;

        return $this;
    }

    public function getEndPeriod(): ?\DateTimeImmutable
    {
        return $this->endPeriod;
    }

    public function setEndPeriod(\DateTimeImmutable $endPeriod): static
    {
        $this->endPeriod = $endPeriod;

        return $this;
    }

    /** @return Collection<int, TimesheetWorkDay> */
    public function getWorkDays(): Collection
    {
        return $this->workDays;
    }

    public function addWorkDay(TimesheetWorkDay $workDay): self
    {
        if (!$this->workDays->contains($workDay)) {
            $this->workDays->add($workDay);
            $workDay->setTimesheet($this);
        }

        return $this;
    }

    public function removeWorkDay(TimesheetWorkDay $workDay): self
    {
        if ($this->workDays->removeElement($workDay) && $workDay->getTimesheet() === $this) {
            $workDay->setTimesheet($this);
        }

        return $this;
    }

    public function setUuid(Uuid $uuid): Timesheet
    {
        $this->uuid = $uuid;

        return $this;
    }

    public function getUuid(): Uuid
    {
        return $this->uuid;
    }

    public function isOwner(User $employee): bool
    {
        return $employee->getUserIdentifier() === $this->getEmployee()?->getUserIdentifier();
    }

    public function getTotalTime(): ?float
    {
        return $this->totalTime;
    }

    public function setTotalTime(float $totalTime): static
    {
        $this->totalTime = $totalTime;

        return $this;
    }

    public function computeTotalTime(): float
    {
        $total = 0;

        foreach ($this->workDays as $workDay) {
            $total += $workDay->getProjectTime();
        }

        return $total;
    }
}
