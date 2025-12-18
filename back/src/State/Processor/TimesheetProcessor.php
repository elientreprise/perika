<?php

namespace App\State\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Dto\Response\Timesheet\TimesheetCreatedResponse;
use App\Entity\Timesheet;
use App\Entity\TimesheetComment;
use App\Entity\User;
use App\Enum\Entity\TimesheetStatusEnum;
use App\Enum\PermissionEnum;
use App\Enum\ResponseMessage\SuccessMessageEnum;
use App\Security\TimesheetVoter;
use App\Service\TimesheetService;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;

readonly class TimesheetProcessor implements ProcessorInterface
{
    public function __construct(
        #[Autowire(service: 'api_platform.doctrine.orm.state.persist_processor')]
        private ProcessorInterface $persistProcessor,
        private TimesheetService $timesheetService,
        private Security $security,
        private AccessDecisionManagerInterface $decisionManager,
    ) {
    }

    /**
     * @return User|void
     */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): mixed
    {
        if (!$data instanceof Timesheet) {
            return $data;
        }

        if (!$this->security->isGranted(PermissionEnum::CAN_CREATE_TIMESHEET->value, $data)) {
            throw new AccessDeniedHttpException($this->getVoterReason(TimesheetVoter::class));
        }

        $user = $this->security->getUser();

        [$start, $end] = $this->timesheetService->timesheetPeriodCalculator($data->getEndPeriod());

        $data
            ->setStartPeriod($start)
            ->setEndPeriod($end)
        ;

        $data->getComments()->map(function (TimesheetComment $comment) use ($user) {
            return $comment->setCreatedBy($user);
        });

        $data->setStatus(TimesheetStatusEnum::SUBMITTED);

        /** @var Timesheet $timesheet */
        $timesheet = $this->persistProcessor->process($data, $operation, $uriVariables, $context);

        return new JsonResponse(new TimesheetCreatedResponse(
            message: SuccessMessageEnum::TS_SUBMITTED->value,
            preventEditing: true,
            uuid: $timesheet->getUuid(),
            employeeUuid: $timesheet->getEmployee()?->getUuid()
        ));
    }

    /**
     * Récupère la première raison de refus pour un voter donné.
     */
    private function getVoterReason(string $voterClass): string
    {
        $decisionLog = $this->decisionManager->getDecisionLog();

        foreach ($decisionLog as $logEntry) {
            if (!empty($logEntry['voterDetails'])) {
                foreach ($logEntry['voterDetails'] as $voterDetail) {
                    if ($voterDetail['voter'] instanceof $voterClass && !empty($voterDetail['reasons'])) {
                        return $voterDetail['reasons'][0];
                    }
                }
            }
        }

        return 'Access Denied';
    }
}
