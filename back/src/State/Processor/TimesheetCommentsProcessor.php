<?php

namespace App\State\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Dto\Entity\CommentCreatedByView;
use App\Dto\Entity\TimesheetCommentView;
use App\Dto\Response\Timesheet\TimesheetCommentAddedResponse;
use App\Entity\TimesheetComment;
use App\Entity\User;
use App\Enum\PermissionEnum;
use App\Enum\ResponseMessage\ErrorMessageEnum;
use App\Enum\ResponseMessage\SuccessMessageEnum;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

readonly class TimesheetCommentsProcessor implements ProcessorInterface
{
    public function __construct(
        #[Autowire(service: 'api_platform.doctrine.orm.state.persist_processor')]
        private ProcessorInterface $persistProcessor,
        private Security $security,
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

        $timesheet = $data->getTimesheet();

        $user = $this->security->getUser();

        if (!$this->security->isGranted(PermissionEnum::CAN_ADD_TIMESHEET_COMMENT->value, $timesheet)) {
            throw new AccessDeniedHttpException(ErrorMessageEnum::TS_COMMENT_ADD_NOT_AUTHORIZED->value);
        }

        $data
            ->setCreatedBy($user)
        ;

        /** @var TimesheetComment $comment */
        $comment = $this->persistProcessor->process($data, $operation, $uriVariables, $context);

        return new JsonResponse(new TimesheetCommentAddedResponse(
            message: SuccessMessageEnum::TS_COMMENT_ADDED->value,
            comment: new TimesheetCommentView(
                uuid: $comment->getUuid(),
                comment: $comment->getComment(),
                createdBy: new CommentCreatedByView(
                    uuid: $comment->getCreatedBy()?->getUuid(),
                    fullName: $comment->getCreatedBy()?->getFullName()
                ),
                createdAt: $comment->getCreatedAt(),
                formattedCreatedAt: $comment->getFormattedCreatedAt(),
                translateStatus: $comment->getTranslateStatus()
            ),
        ));
    }
}
