<?php

namespace App\Dto\Response\Timesheet;

use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Uid\Uuid;

class TimesheetCommentAddedResponse
{
    public function __construct(
        #[Groups(['timesheet:comment:read'])]
        public string $message,
        #[Groups(['timesheet:comment:read'])]
        public Uuid $uuid,
        #[Groups(['timesheet:comment:read'])]
        public Uuid $employeeUuid,
    ) {
    }
}
