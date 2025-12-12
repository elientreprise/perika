<?php
// src/State/EmployeeTimesheetProvider.php

namespace App\State\Provider;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Entity\Timesheet;
use App\Repository\TimesheetRepository;
use App\Service\TimesheetService;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Uid\Uuid;

/**
 * @implements ProviderInterface<Timesheet>
 */
readonly class EmployeeTimesheetProvider implements ProviderInterface
{
    public function __construct(
        private TimesheetService $timesheetService
    ) {}

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        $employeeUuid = $uriVariables['employeeUuid'];
        /** @var Uuid $timesheetUuid */
        $timesheetUuid = $uriVariables['uuid'];

        $timesheet = $this->timesheetService->getByEmployee(
            $employeeUuid, $timesheetUuid->toRfc4122()
        );

        if (!$timesheet) {
            throw new NotFoundHttpException('Timesheet not found for this employee');
        }

        return $timesheet;
    }
}
