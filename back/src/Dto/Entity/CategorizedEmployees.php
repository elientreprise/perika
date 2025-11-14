<?php

namespace App\Dto\Entity;

use App\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

final class CategorizedEmployees
{
    private Collection $orphans;
    private Collection $alreadyLinked;
    private Collection $notFounds;

    public function __construct()
    {
        $this->orphans = new ArrayCollection();
        $this->alreadyLinked = new ArrayCollection();
        $this->notFounds = new ArrayCollection();
    }

    public function getOrphans(): Collection
    {
        return $this->orphans;
    }

    public function getAlreadyLinked(): Collection
    {
        return $this->alreadyLinked;
    }

    public function getNotFounds(): Collection
    {
        return $this->notFounds;
    }

    public function addNotFound(string $uuid): void
    {
        if (!$this->notFounds->contains($uuid)) {
            $this->notFounds->add($uuid);
        }
    }

    public function addOrphans(User $employee): void
    {
        if (!$this->orphans->contains($employee)) {
            $this->orphans->add($employee);
        }
    }

    public function addAlreadyLinked(User $employee): void
    {
        if (!$this->alreadyLinked->contains($employee)) {
            $this->alreadyLinked->add($employee);
        }
    }

    public function countOrphans(): int
    {
        return $this->orphans->count();
    }

    public function countAlreadyLinked(): int
    {
        return $this->alreadyLinked->count();
    }

    public function countNotFounds(): int
    {
        return $this->notFounds->count();
    }
}
