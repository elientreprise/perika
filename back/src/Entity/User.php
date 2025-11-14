<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\OpenApi\Model\Operation as OpenApiOperation;
use ApiPlatform\OpenApi\Model\RequestBody;
use App\Controller\CheckAuthAction;
use App\Dto\Response\LinkEmployeeResponse;
use App\Enum\PermissionEnum;
use App\Repository\UserRepository;
use App\State\LinkEmployeeProcessor;
use App\State\MeProvider;
use App\State\Processor\UserProcessor;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[ApiResource(
    operations: [
        new Post(
            uriTemplate: '/register',
            normalizationContext: ['groups' => ['employee:read']],
            denormalizationContext: ['groups' => ['employee:write']],
            validationContext: ['groups' => ['Default', 'create']],
            processor: UserProcessor::class
        ),
        new Patch(
            uriTemplate: '/employees/{uuid}',
            normalizationContext: ['groups' => ['employee:read']],
            denormalizationContext: ['groups' => ['edit']],
            security: "is_granted('".PermissionEnum::CAN_EDIT_EMPLOYEE->value."', object)"
        ),
        new Get(
            uriTemplate: '/employees/{uuid}',
            normalizationContext: ['groups' => ['employee:read']],
            security: "is_granted('".PermissionEnum::CAN_VIEW_EMPLOYEE->value."', object)"
        ),
        new Get(
            uriTemplate: '/me',
            normalizationContext: ['groups' => ['employee:read']],
            provider: MeProvider::class
        ),
        new Get(
            uriTemplate: '/authenticated',
            controller: CheckAuthAction::class,
            normalizationContext: ['groups' => ['employee:read']]
        ),
        new Patch(
            uriTemplate: '/managers/{uuid}/link-employee',
            openapi: new OpenApiOperation(
                summary: 'Link multiple employees to a manager',
                requestBody: new RequestBody(
                    content: new \ArrayObject([
                        'application/json' => [
                            'schema' => [
                                'type' => 'object',
                                'properties' => [
                                    'employeeUuids' => [
                                        'type' => 'array',
                                        'items' => ['type' => 'string', 'format' => 'uuid'],
                                    ],
                                ],
                                'required' => ['employeeUuids'],
                            ],
                        ],
                    ])
                )
            ),
            normalizationContext: ['groups' => ['link-employee:read']],
            denormalizationContext: ['groups' => ['link-employee:write']],
            security: "is_granted('".PermissionEnum::CAN_ADD_SUBORDINATES->value."', object)",
            output: LinkEmployeeResponse::class,
            processor: LinkEmployeeProcessor::class
        ),
    ],
    normalizationContext: ['groups' => ['employee:read', 'link-employee:read']],
    denormalizationContext: ['groups' => ['employee:write', 'link-employee:write']],
    validationContext: ['groups' => 'Default', 'edit', 'link-employee:write']
)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer', unique: true)]
    #[ApiProperty(identifier: false)]
    private ?int $id = null;

    #[ORM\Column(type: 'uuid', unique: true)]
    #[ApiProperty(identifier: true)]
    #[Groups(['employee:read', 'link-employee:read'])]
    private Uuid $uuid;

    #[ORM\Column(type: 'string', length: 180, unique: true)]
    #[Assert\NotBlank(groups: ['create'])]
    #[Assert\Email(groups: ['create'])]
    #[Groups(['employee:read', 'employee:write'])]
    private ?string $email;

    #[ORM\Column(type: 'string')]
    #[Groups(['employee:write'])]
    #[Assert\NotBlank]
    private string $password;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['employee:read', 'edit'])]
    #[Assert\NotBlank(message: 'The firstName are required', groups: ['edit'])]
    private ?string $firstName = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['employee:read', 'edit'])]
    #[Assert\NotBlank(message: 'The lastName are required', groups: ['edit'])]
    private ?string $lastName = null;

    #[ORM\Column(type: 'json', nullable: true)]
    private array $roles = [];

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotBlank(message: 'The position are required', groups: ['edit'])]
    #[Groups(['employee:read', 'edit'])]
    private ?string $position = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, nullable: true)]
    #[Assert\NotBlank(message: 'The salary are required', groups: ['edit'])]
    #[Groups(['employee:read', 'edit'])]
    private ?string $salary = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE, nullable: true)]
    #[Assert\NotBlank(message: 'The hire date are required', groups: ['edit'])]
    #[Groups(['employee:read', 'edit'])]
    private ?\DateTimeImmutable $hire_date = null;

    #[ORM\Column]
    #[Groups(['employee:read'])]
    private ?bool $isActive = null;

    #[ORM\Column(length: 20, nullable: true)]
    #[Groups(['employee:read', 'edit'])]
    #[Assert\NotBlank(message: 'The phone number are required', groups: ['edit'])]
    private ?string $phoneNumber = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE, nullable: true)]
    #[Groups(['employee:read', 'edit'])]
    private ?\DateTimeImmutable $birthDate = null;

    #[ORM\ManyToOne(targetEntity: self::class)]
    #[Groups(['employee:read'])]
    private ?User $manager = null;

    #[ORM\OneToMany(targetEntity: self::class, mappedBy: 'manager')]
    #[Groups(['manager:read'])]
    private Collection $subordinates;

    #[Assert\NotBlank(groups: ['link-employee:write'])]
    #[Assert\Count(min: 1, groups: ['link-employee:write'])]
    #[Assert\All(
        constraints: [
            new Assert\Uuid(),
        ],
        groups: ['link-employee:write'])
    ]
    #[Groups(['link-employee:write'])]
    public array $employeeUuids = [];

    public function __construct()
    {
        $this->uuid = Uuid::v4();
        $this->isActive = true;
        $this->subordinates = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    #[\Override]
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    #[\Override]
    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    #[\Override]
    public function eraseCredentials(): void
    {
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): static
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getPosition(): ?string
    {
        return $this->position;
    }

    public function setPosition(string $position): static
    {
        $this->position = $position;

        return $this;
    }

    public function getSalary(): ?string
    {
        return $this->salary;
    }

    public function setSalary(?string $salary): static
    {
        $this->salary = $salary;

        return $this;
    }

    public function getHireDate(): ?\DateTimeImmutable
    {
        return $this->hire_date;
    }

    public function setHireDate(?\DateTimeImmutable $hire_date): static
    {
        $this->hire_date = $hire_date;

        return $this;
    }

    public function isActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): static
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function getUuid(): ?Uuid
    {
        return $this->uuid;
    }

    public function setPhoneNumber(?string $phoneNumber): User
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function getBirthDate(): ?\DateTimeImmutable
    {
        return $this->birthDate;
    }

    public function setBirthDate(?\DateTimeImmutable $birthDate): User
    {
        $this->birthDate = $birthDate;

        return $this;
    }

    public function setManager(?User $manager): User
    {
        $this->manager = $manager;

        return $this;
    }

    public function getManager(): ?User
    {
        return $this->manager;
    }

    public function getSubordinates(): Collection
    {
        return $this->subordinates;
    }

    public function addSubordinate(User $employee): void
    {
        $employee->setManager($this);

        if (!$this->subordinates->contains($employee)) {
            $this->subordinates->add($employee);
        }
    }

    public function removeSubordinate(User $employee): void
    {
        $this->subordinates->remove($employee);
    }

    #[Groups(['employee:read', 'link-employee:read'])]
    /**
     * @return string
     *                Concat firstname and lastname
     */
    public function getFullName(): string
    {
        return sprintf('%s %s', $this->firstName, $this->lastName);
    }

    #[Groups(['employee:read'])]
    /**
     * @return string
     *                Return seniority calculated between hire_date and now
     *                format on "%y years %m months %d days"
     */
    public function getSeniority(): string
    {
        $now = new \DateTimeImmutable('now');

        if (!$this->hire_date) {
            return 0;
        }

        $interval = date_diff($this->hire_date, $now);

        return $interval->format('%y years %m months %d days');
    }
}
