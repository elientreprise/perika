<?php

namespace App\Story;

use App\Factory\TimesheetFactory;
use App\Factory\UserFactory;
use Zenstruck\Foundry\Story;

final class DefaultUseCaseStory extends Story
{
    public function build(): void
    {
        $rh = UserFactory::new([
            'email' => 'rh@example.com',
            'roles' => ['ROLE_RH'],
        ])->create();

        $manager = UserFactory::new([
            'email' => 'manager@example.com',
            'roles' => ['ROLE_MANAGER'],
        ])->create();

        $employee = UserFactory::new([
            'email' => 'employee@example.com',
            'roles' => ['ROLE_USER'],
            'manager' => $manager,
        ])->create();

        TimesheetFactory::new([
            'employee' => $employee,
        ])->withWorkDays()->create();
    }
}
