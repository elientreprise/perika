<?php

namespace App\EventListener;

use App\Entity\Timesheet;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener(event: PrePersistEventArgs::class, method: 'prePersist')]
#[AsEventListener(event: PreUpdateEventArgs::class, method: 'preUpdate')]
class TimesheetListener
{
    public function prePersist(Timesheet $timesheet, PrePersistEventArgs $event): void
    {
        $timesheet->setTotalTime($timesheet->computeTotalTime());
    }

    public function preUpdate(Timesheet $timesheet, PreUpdateEventArgs $event): void
    {
        $timesheet->setUpdatedAt(new \DateTime());
        $timesheet->setTotalTime($timesheet->computeTotalTime());

        $entityManager = $event->getObjectManager();
        $entityManager->getUnitOfWork()->recomputeSingleEntityChangeSet(
            $entityManager->getClassMetadata(Timesheet::class),
            $timesheet
        );
    }
}
