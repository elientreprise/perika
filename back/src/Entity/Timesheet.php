<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Link;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Dto\Input\CalculatePeriodInput;
use App\Dto\Input\CheckTimesheetInput;
use App\Dto\Response\Timesheet\CheckTimesheetResponse;
use App\Dto\Response\Timesheet\TimesheetCalculatePeriodResponse;
use App\Entity\Interface\TimesheetStatusInterface;
use App\Entity\Trait\DateFormatterTrait;
use App\Entity\Trait\TimestampableTrait;
use App\Enum\Entity\CommentStatusEnum;
use App\Enum\Entity\TimesheetStatusEnum;
use App\Enum\PermissionEnum;
use App\Filter\TimesheetSearchFilter;
use App\Repository\TimesheetRepository;
use App\State\Processor\CheckTimesheetExistsProcessor;
use App\State\Processor\TimesheetCalculatePeriodProcessor;
use App\State\Processor\TimesheetProcessor;
use App\State\Processor\TimesheetValidProcessor;
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
        new Patch(
            uriTemplate: '/timesheets/{uuid}/valid',
            normalizationContext: ['groups' => ['timesheet:read']],
            input: false,
            processor: TimesheetValidProcessor::class
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
                'uuid' => 'uuid',
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
        ),
    ],
    normalizationContext: ['groups' => ['timesheet:read', 'timesheet:item:read']],
    denormalizationContext: ['groups' => ['timesheet:write', 'timesheet:calculate-period']],
    validationContext: ['groups' => 'Default', 'create', 'edit']
)]
#[ValidStartEndDate(groups: ['timesheet:write'])]
#[ValidTimesheet(groups: ['timesheet:write'])]
#[NoTimesheetOverlap(groups: ['timesheet:write'])]
#[ApiFilter(TimesheetSearchFilter::class, properties: [
    'uuid',
    'startPeriod',
    'endPeriod',
    'status',
])]
class Timesheet implements TimesheetStatusInterface
{
    use TimestampableTrait;
    use DateFormatterTrait;

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
    #[Assert\NotBlank(groups: ['timesheet:write'])]
    private ?User $employee = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    #[Groups(['timesheet:item:read'])]
    #[Assert\NotBlank(groups: ['timesheet:write'])]
    private ?\DateTimeImmutable $startPeriod = null;
    #[Groups(['timesheet:read'])]
    private ?string $formattedStartPeriod = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    #[Groups(['timesheet:read', 'timesheet:write'])]
    #[Assert\NotBlank(groups: ['timesheet:write'])]
    private ?\DateTimeImmutable $endPeriod = null;

    #[Groups(['timesheet:read'])]
    private ?string $formattedEndPeriod = null;

    /** @var Collection<int, TimesheetWorkDay> */
    #[ORM\OneToMany(targetEntity: TimesheetWorkDay::class, mappedBy: 'timesheet', cascade: ['persist'], orphanRemoval: true)]
    #[Groups(['timesheet:read', 'timesheet:write'])]
    #[Assert\Valid(groups: ['timesheet:write'])]
    private Collection $workDays;

    #[ORM\Column(options: ['default' => 0])]
    #[Groups(['timesheet:item:read'])]
    private float $totalTime = 0;

    #[ORM\Column(enumType: TimesheetStatusEnum::class, options: ['default' => TimesheetStatusEnum::DRAFT])]
    #[Groups(['timesheet:item:read'])]
    private ?TimesheetStatusEnum $status = TimesheetStatusEnum::DRAFT;

    #[Groups(['timesheet:item:read', 'timesheet:read'])]
    private ?string $translateStatus = null;

    #[Groups(['timesheet:item:read', 'timesheet:read'])]
    private ?string $formattedCreatedAt = null;

    /**
     * @var Collection<int, TimesheetComment>
     */
    #[Groups(['timesheet:write', 'timesheet:read'])]
    #[ORM\OrderBy(['createdAt' => 'DESC'])]
    #[ORM\OneToMany(targetEntity: TimesheetComment::class, mappedBy: 'timesheet', cascade: ['persist'])]
    private Collection $comments;

    public function __construct()
    {
        $this->uuid = Uuid::v4();
        $this->workDays = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
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

    public function getStatus(): ?TimesheetStatusEnum
    {
        return $this->status;
    }

    public function setStatus(TimesheetStatusEnum $status): static
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return Collection<int, TimesheetComment>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    /**
     * @return Collection<int, TimesheetComment>
     */
    public function getNewComments(User $user): Collection
    {
        return $this->comments->filter(function (TimesheetComment $comment) use ($user) {
            return CommentStatusEnum::NEW === $comment->getStatus() && $comment->getCreatedBy()?->getId() !== $user->getId();
        });
    }

    public function addComment(TimesheetComment $comment): static
    {
        if (!$this->comments->contains($comment)) {
            $this->comments->add($comment);
            $comment->setTimesheet($this);
        }

        return $this;
    }

    public function removeComment(TimesheetComment $comment): static
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getTimesheet() === $this) {
                $comment->setTimesheet(null);
            }
        }

        return $this;
    }

    #[Groups(['timesheet:item:read'])]
    public function getThreeLastComments(): Collection
    {
        $length = $this->comments->count();

        return $length > 3 ? new ArrayCollection($this->comments->slice($length, 3)) : $this->comments;
    }

    public function getTranslateStatus(): ?string
    {
        return TimesheetStatusEnum::translate($this->status);
    }

    public function getFormattedCreatedAt(): ?string
    {
        return $this->formatDate($this->createdAt);
    }

    public function getFormattedStartPeriod(): ?string
    {
        return $this->formatDate($this->startPeriod);
    }

    public function getFormattedEndPeriod(): ?string
    {
        return $this->formatDate($this->endPeriod
        );
    }

    /**
     * @return bool
     */
    #[Groups(['timesheet:item:read'])]
    #[\Override] public function isValid(): bool
    {
       return $this->getStatus() === TimesheetStatusEnum::VALID;
    }

    /**
     * @return bool
     */
    #[Groups(['timesheet:item:read'])]
    #[\Override] public function isDraft(): bool
    {
        return $this->getStatus() === TimesheetStatusEnum::DRAFT;
    }

    /**
     * @return bool
     */
    #[Groups(['timesheet:item:read'])]
    #[\Override] public function isNeedEdit(): bool
    {
        return $this->getStatus() === TimesheetStatusEnum::NEED_EDIT;
    }

    /**
     * @return bool
     */
    #[Groups(['timesheet:item:read'])]
    #[\Override] public function isSubmitted(): bool
    {
        return $this->getStatus() === TimesheetStatusEnum::SUBMITTED;
    }
}
