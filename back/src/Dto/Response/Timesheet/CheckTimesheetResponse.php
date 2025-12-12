<?php

namespace App\Dto\Response\Timesheet;

use Symfony\Component\Serializer\Attribute\Groups;

class CheckTimesheetResponse
{
    public function __construct(
        #[Groups(['timesheet:read'])]
        public bool $exists,
        #[Groups(['timesheet:read'])]
        public ?string $message = null,
    ) {
    }
}
