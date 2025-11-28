<?php

namespace App\Dto\Response\Timesheet;

use Symfony\Component\Serializer\Attribute\Groups;

class TimesheetCalculatePeriodResponse
{
    public function __construct(
        #[Groups(['timesheet:read'])]
        public \DateTimeInterface $start,
        #[Groups(['timesheet:read'])]
        public \DateTimeInterface $end,
    ) {
    }
}
