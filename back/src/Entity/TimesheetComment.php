<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Link;
use ApiPlatform\Metadata\Post;
use App\Entity\Trait\DateFormatterTrait;
use App\Entity\Trait\TimestampableTrait;
use App\Enum\Entity\CommentStatusEnum;
use App\Enum\PermissionEnum;
use App\Repository\TimesheetCommentRepository;
use App\State\Processor\TimesheetCommentsProcessor;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: TimesheetCommentRepository::class)]
#[ApiResource(
    operations: [
        new Get(
            uriTemplate: '/timesheet-comment/{uuid}',
            normalizationContext: ['groups' => ['timesheet:comment:read', 'timesheet:comment:item:read']],
        ),
        new Post(
            uriTemplate: '/timesheet-comments',
            normalizationContext: ['groups' => ['timesheet:comment:read']],
            denormalizationContext: ['groups' => ['timesheet:comment:write']],
            output: false,
            processor: TimesheetCommentsProcessor::class
        ),
        new GetCollection(
            uriTemplate: '/timesheets/{timesheetUuid}/comments',
            uriVariables: [
                'timesheetUuid' => new Link(
                    toProperty: 'timesheet',
                    fromClass: Timesheet::class,
                    security: "is_granted('".PermissionEnum::CAN_VIEW_TIMESHEET_COMMENT_COLLECTION->value."', timesheet)"
                ),
            ],
            paginationItemsPerPage: 10,
            paginationFetchJoinCollection: true,
            order: ['createdAt' => 'DESC'],
            normalizationContext: ['groups' => ['timesheet:comment:read', 'timesheet:comment:item:read']]
        ),
    ],
    normalizationContext: ['groups' => ['timesheet:comment:read', 'timesheet:comment:item:read']],
    denormalizationContext: ['groups' => ['timesheet:comment:write']],
    validationContext: ['groups' => 'Default', 'create', 'edit']
)]
class TimesheetComment
{
    use TimestampableTrait;
    use DateFormatterTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[ApiProperty(identifier: false)]
    private ?int $id = null;

    #[ORM\Column(type: 'uuid', unique: true)]
    #[ApiProperty(identifier: true)]
    #[Groups(['timesheet:comment:read'])]
    private Uuid $uuid;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['timesheet:comment:write', 'timesheet:comment:read', 'timesheet:item:read', 'timesheet:write'])]
    // todo : ajouter assert custom pour montrer comment ecrire la propertypath
    private ?string $propertyPath = null;

    #[ORM\Column(length: 255)]
    #[Groups(['timesheet:comment:write', 'timesheet:comment:read', 'timesheet:item:read', 'timesheet:write'])]
    #[Assert\NotBlank()]
    private ?string $comment = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['timesheet:item:read', 'timesheet:comment:read'])]
    private ?User $createdBy = null;

    #[ORM\ManyToOne(inversedBy: 'comments')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['timesheet:comment:write'])]
    #[Assert\NotBlank()]
    private ?Timesheet $timesheet = null;

    #[ORM\Column(enumType: CommentStatusEnum::class, options: ['default' => CommentStatusEnum::NEW])]
    #[Groups(['timesheet:comment:read', 'timesheet:item:read'])]
    private ?CommentStatusEnum $status = CommentStatusEnum::NEW;

    #[Groups(['timesheet:comment:read', 'timesheet:item:read'])]
    private ?string $translateStatus = null;

    #[Groups(['timesheet:comment:read', 'timesheet:item:read'])]
    private ?string $formattedCreatedAt = null;

    public function __construct()
    {
        $this->uuid = Uuid::v4();
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPropertyPath(): ?string
    {
        return $this->propertyPath;
    }

    public function setPropertyPath(?string $propertyPath): static
    {
        $this->propertyPath = $propertyPath;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(string $comment): static
    {
        $this->comment = $comment;

        return $this;
    }

    public function getCreatedBy(): ?User
    {
        return $this->createdBy;
    }

    public function setCreatedBy(?User $createdBy): static
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    public function getTimesheet(): ?Timesheet
    {
        return $this->timesheet;
    }

    public function setTimesheet(?Timesheet $timesheet): static
    {
        $this->timesheet = $timesheet;

        return $this;
    }

    public function getUuid(): Uuid
    {
        return $this->uuid;
    }

    #[Groups(['timesheet:item:read', 'timesheet:comment:read'])]
    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }

    public function getStatus(): ?CommentStatusEnum
    {
        return $this->status;
    }

    public function setStatus(CommentStatusEnum $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getTranslateStatus(): ?string
    {
        return CommentStatusEnum::translate($this->status);
    }

    public function getFormattedCreatedAt(): ?string
    {
        return $this->formatDate($this->createdAt);
    }
}
