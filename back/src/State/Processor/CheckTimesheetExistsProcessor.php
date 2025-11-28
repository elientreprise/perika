<?php

namespace App\State\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Dto\Input\CheckTimesheetInput;
use App\Dto\Response\Timesheet\CheckTimesheetResponse;
use App\Service\TimesheetService;

readonly class CheckTimesheetExistsProcessor implements ProcessorInterface
{
    public function __construct(
        private TimesheetService $timesheetService,
    ) {
    }

    #[\Override]
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): CheckTimesheetResponse
    {
        /* @var CheckTimesheetInput $data */
        [$start, $end] = $this->timesheetService->timesheetPeriodCalculator($data->date);

        $exists = $this->timesheetService->getOverlapTimesheet($data->employee, $start, $end);

        return new CheckTimesheetResponse($exists);
    }
}
