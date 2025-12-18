<?php

namespace App\Repository;

use App\Entity\Timesheet;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Timesheet>
 */
class TimesheetRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Timesheet::class);
    }

    public function hasOverlap(User $employee, \DateTimeImmutable $startPeriod, \DateTimeImmutable $endPeriod): bool
    {
        return $this->createQueryBuilder('t')
                ->select('COUNT(t.id)')
                ->andWhere('t.employee = :employee')
                ->andWhere('t.endPeriod >= :start')
                ->andWhere('t.startPeriod <= :end')
                ->setParameter('employee', $employee)
                ->setParameter('start', $startPeriod)
                ->setParameter('end', $endPeriod)
                ->getQuery()
                ->getSingleScalarResult() > 0;
    }

    public function findOneByEmployee(string $employeeUuid, string $timesheetUuid): ?Timesheet
    {
        return $this->createQueryBuilder('t')
                ->join('t.employee', 'e')
                ->andWhere('e.uuid = :employeeUuid')
                ->andWhere('t.uuid = :timesheetUuid')
                ->setParameter('employeeUuid', $employeeUuid)
                ->setParameter('timesheetUuid', $timesheetUuid)
                ->getQuery()
                ->getOneOrNullResult();
    }
}
