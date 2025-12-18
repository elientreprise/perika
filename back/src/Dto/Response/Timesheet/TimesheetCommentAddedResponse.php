<?php

namespace App\Dto\Response\Timesheet;

use App\Dto\Entity\TimesheetCommentView;
use Symfony\Component\Serializer\Attribute\Groups;

class TimesheetCommentAddedResponse
{
    public function __construct(
        #[Groups(['timesheet:comment:read'])]
        public string $message,
        #[Groups(['timesheet:comment:read'])]
        public TimesheetCommentView $comment,
    ) {
    }
}
