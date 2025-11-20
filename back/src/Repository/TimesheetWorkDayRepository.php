<?php

namespace App\Repository;

use App\Entity\TimesheetWorkDay;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TimesheetWorkDay|null find($id, $lockMode = null, $lockVersion = null)
 * @method TimesheetWorkDay|null findOneBy(array $criteria, array $orderBy = null)
 * @method TimesheetWorkDay[] findAll()
 * @method TimesheetWorkDay[] findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TimesheetWorkDayRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TimesheetWorkDay::class);
    }
}
