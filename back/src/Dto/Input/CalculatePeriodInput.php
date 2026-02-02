<?php

namespace App\Dto\Input;

use Symfony\Component\Serializer\Attribute\Groups;

class CalculatePeriodInput
{
    #[Groups(['timesheet:calculate-period'])]
    public \DateTimeInterface $date;
}
