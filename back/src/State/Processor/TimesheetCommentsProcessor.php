<?php

namespace App\State\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Dto\Response\Timesheet\TimesheetCommentAddedResponse;
use App\Dto\Response\Timesheet\TimesheetCreatedResponse;
use App\Entity\Timesheet;
use App\Entity\TimesheetComment;
use App\Entity\User;
use App\Enum\PermissionEnum;
use App\Enum\ResponseMessage\SuccessMessageEnum;
use App\Security\TimesheetVoter;
use App\Service\TimesheetService;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;

readonly class TimesheetCommentsProcessor implements ProcessorInterface
{
    public function __construct(
        #[Autowire(service: 'api_platform.doctrine.orm.state.persist_processor')]
        private ProcessorInterface $persistProcessor,
        private Security $security,
        private AccessDecisionManagerInterface $decisionManager,
    ) {
    }

    /**
     * @return User|void
     */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): mixed
    {
        if (!$data instanceof TimesheetComment) {
            return $data;
        }

        $user = $this->security->getUser();

        if (!$this->security->isGranted(PermissionEnum::CAN_ADD_TIMESHEET_COMMENT->value, $data)) {
            throw new AccessDeniedHttpException('access denied');
        }

        $data
            ->setCreatedBy($user)
        ;

        /** @var TimesheetComment $comment */
        $comment = $this->persistProcessor->process($data, $operation, $uriVariables, $context);

        return new JsonResponse(new TimesheetCommentAddedResponse(
            message: SuccessMessageEnum::TS_COMMENT_ADDED->value,
            uuid: $comment->getTimesheet()?->getUuid(),
            employeeUuid: $comment->getCreatedBy()?->getUuid()
        ));
    }
}
