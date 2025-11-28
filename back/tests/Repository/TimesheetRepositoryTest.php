<?php

namespace App\Tests\Repository;

use App\Entity\Timesheet;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TimesheetRepositoryTest extends KernelTestCase
{
    private EntityManagerInterface $entityManager;
    private $repository;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->entityManager = self::getContainer()->get(EntityManagerInterface::class);
        $this->repository = $this->entityManager->getRepository(Timesheet::class);

        $this->entityManager->createQuery('DELETE FROM App\Entity\Timesheet t')->execute();
        $this->entityManager->createQuery('DELETE FROM App\Entity\User u')->execute();
    }

    public function testHasOverlapReturnsTrue(): void
    {
        $user = new User();
        $user->setEmail('employee@example.com');
        $user->setPassword('test');
        $this->entityManager->persist($user);

        $existingTimesheet = new Timesheet();
        $existingTimesheet->setEmployee($user)
            ->setStartPeriod(new \DateTimeImmutable('2024-01-01'))
            ->setEndPeriod(new \DateTimeImmutable('2024-01-07'));

        $this->entityManager->persist($existingTimesheet);
        $this->entityManager->flush();

        $result = $this->repository->hasOverlap(
            $user,
            new \DateTimeImmutable('2024-01-05'),
            new \DateTimeImmutable('2024-01-10')
        );

        $this->assertTrue($result);
    }

    public function testHasOverlapReturnsFalse(): void
    {
        $user = new User();
        $user->setEmail('employee@example.com');
        $user->setPassword('test');
        $this->entityManager->persist($user);

        $existingTimesheet = new Timesheet();
        $existingTimesheet->setEmployee($user)
            ->setStartPeriod(new \DateTimeImmutable('2024-01-01'))
            ->setEndPeriod(new \DateTimeImmutable('2024-01-07'));

        $this->entityManager->persist($existingTimesheet);
        $this->entityManager->flush();

        $result = $this->repository->hasOverlap(
            $user,
            new \DateTimeImmutable('2024-01-08'),
            new \DateTimeImmutable('2024-01-10')
        );

        $this->assertFalse($result);
    }
}
