<?php

namespace App\Entity\Trait;

trait DateFormatterTrait
{
    /**
     * @return string
     *                Return formatted date to dd/mm/yyyy
     */
    private function formatDate(\DateTimeInterface $dateTime)
    {
        return $dateTime->format('d/m/Y');
    }
}
