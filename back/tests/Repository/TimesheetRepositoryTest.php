<?php

namespace App\Tests\Repository;

use App\Entity\Timesheet;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TimesheetRepositoryTest extends KernelTestCase
{
    private EntityManagerInterface $entityManager;
    private $timesheetRepository;

    protected function setUp(): void
    {
        self::bootKernel();

        $this->entityManager = self::getContainer()->get(EntityManagerInterface::class);
        $this->timesheetRepository = $this->entityManager->getRepository(Timesheet::class);

        $this->entityManager->createQuery('DELETE FROM App\Entity\Timesheet')->execute();
        $this->entityManager->createQuery('DELETE FROM App\Entity\User')->execute();
    }

    private function createUser(): User
    {
        $user = (new User())
            ->setEmail('employee@example.com')
            ->setPassword('test');

        $this->entityManager->persist($user);

        return $user;
    }

    private function createTimesheet(User $user, string $start, string $end): Timesheet
    {
        $ts = (new Timesheet())
            ->setEmployee($user)
            ->setStartPeriod(new \DateTimeImmutable($start))
            ->setEndPeriod(new \DateTimeImmutable($end));

        $this->entityManager->persist($ts);

        return $ts;
    }

    public function testHasOverlapReturnsTrue(): void
    {
        $user = $this->createUser();
        $this->createTimesheet($user, '2024-01-01', '2024-01-07');
        $this->entityManager->flush();

        $result = $this->timesheetRepository->hasOverlap(
            $user,
            new \DateTimeImmutable('2024-01-05'),
            new \DateTimeImmutable('2024-01-10')
        );

        $this->assertTrue($result);
    }

    public function testHasOverlapReturnsFalse(): void
    {
        $user = $this->createUser();
        $this->createTimesheet($user, '2024-01-01', '2024-01-07');
        $this->entityManager->flush();

        $result = $this->timesheetRepository->hasOverlap(
            $user,
            new \DateTimeImmutable('2024-01-08'),
            new \DateTimeImmutable('2024-01-10')
        );

        $this->assertFalse($result);
    }
}
