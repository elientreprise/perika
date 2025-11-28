<?php

namespace App\State\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Dto\Input\CalculatePeriodInput;
use App\Dto\Response\Timesheet\TimesheetCalculatePeriodResponse;
use App\Entity\User;
use App\Service\TimesheetService;

readonly class TimesheetCalculatePeriodProcessor implements ProcessorInterface
{
    public function __construct(
        private TimesheetService $timesheetService,
    ) {
    }

    /**
     * @return User|void
     */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): mixed
    {
        /** @var CalculatePeriodInput $data */
        if (!$data instanceof CalculatePeriodInput) {
            return $data;
        }

        [$start, $end] = $this->timesheetService->timesheetPeriodCalculator($data->date);

        return new TimesheetCalculatePeriodResponse($start, $end);
    }
}
