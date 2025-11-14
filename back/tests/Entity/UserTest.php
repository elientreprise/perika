<?php

namespace App\Tests\Entity;

use App\Entity\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testFullName(): void
    {
        $user = new User();
        $user->setFirstName('John');
        $user->setLastName('Doe');

        $this->assertEquals('John Doe', $user->getFullName());
    }

    public function testSeniorityWithoutHireDate(): void
    {
        $user = new User();
        $this->assertEquals(0, $user->getSeniority());
    }

    public function testSeniorityWithHireDate(): void
    {
        $user = new User();
        $user->setHireDate(new \DateTimeImmutable('-2 years -3 months'));
        $seniority = $user->getSeniority();

        $this->assertStringContainsString('2 years', $seniority);
        $this->assertStringContainsString('3 months', $seniority);
    }
}
