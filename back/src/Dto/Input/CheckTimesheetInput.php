<?php

namespace App\Dto\Input;

use App\Entity\User;
use Symfony\Component\Serializer\Attribute\Groups;

class CheckTimesheetInput
{
    #[Groups(['timesheet:check-exists'])]
    public User $employee;
    #[Groups(['timesheet:check-exists'])]
    public \DateTimeInterface $date;
}
