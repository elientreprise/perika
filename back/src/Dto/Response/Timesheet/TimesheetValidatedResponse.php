<?php

namespace App\Dto\Response\Timesheet;

use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Uid\Uuid;

class TimesheetValidatedResponse
{
    public function __construct(
        #[Groups(['timesheet:read'])]
        public string $message,
        #[Groups(['timesheet:read'])]
        public Uuid $uuid,
    ) {
    }
}
