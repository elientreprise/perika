<?php

namespace App\Entity\Interface;

interface TimesheetStatusInterface
{
    public function isValid(): bool;
    public function isDraft(): bool;
    public function isNeedEdit(): bool;
    public function isSubmitted(): bool;
}
