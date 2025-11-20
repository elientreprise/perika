<?php

namespace App\Entity;

use App\Repository\TimesheetRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TimesheetRepository::class)]
class Timesheet
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'timesheets')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $employee = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    private ?\DateTimeImmutable $startPeriod = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    private ?\DateTimeImmutable $endPeriod = null;

    #[ORM\OneToMany(targetEntity: TimesheetWorkDay::class, mappedBy: 'timesheet', cascade: ['persist'], orphanRemoval: true)]
    private Collection $workDays;

    public function __construct()
    {
        $this->workDays = new ArrayCollection();
    }

    public function getId(): ?int
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

    public function getWorkDays(): Collection
    {
        return $this->workDays;
    }

    public function addWorkDay(WorkDay $workDay): self
    {
        if (!$this->workDays->contains($workDay)) {
            $this->workDays->add($workDay);
            $workDay->setTimesheet($this);
        }

        return $this;
    }

    public function removeWorkDay(WorkDay $workDay): self
    {
        if ($this->workDays->removeElement($workDay) && $workDay->getTimesheet() === $this) {
            $workDay->setTimesheet($this);
        }

        return $this;
    }
}
