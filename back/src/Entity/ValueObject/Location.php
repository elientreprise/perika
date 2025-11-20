<?php

namespace App\Entity\ValueObject;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
class Location
{
    #[ORM\Column(nullable: true)]
    private ?string $am = null;

    #[ORM\Column(nullable: true)]
    private ?string $pm = null;

    public function getAm(): ?string
    {
        return $this->am;
    }

    public function setAm(?string $am): self
    {
        $this->am = $am;

        return $this;
    }

    public function getPm(): ?string
    {
        return $this->pm;
    }

    public function setPm(?string $pm): self
    {
        $this->pm = $pm;

        return $this;
    }
}
