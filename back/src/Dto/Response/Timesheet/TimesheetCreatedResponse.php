<?php

namespace App\Dto\Response\Timesheet;

use Symfony\Component\Serializer\Attribute\Groups;

class TimesheetCreatedResponse
{
    public function __construct(
        #[Groups(['timesheet:read'])]
        public string $message,
        #[Groups(['timesheet:read'])]
        public bool $preventEditing,
    ) {
    }
}
