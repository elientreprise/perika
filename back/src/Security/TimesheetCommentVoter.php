<?php

namespace App\Security;

use App\Entity\Timesheet;
use App\Entity\TimesheetComment;
use App\Entity\User;
use App\Enum\PermissionEnum;
use App\Enum\ResponseMessage\ErrorMessageEnum;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Vote;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class TimesheetCommentVoter extends Voter
{
    protected function supports(string $attribute, mixed $subject): bool
    {
        if (!in_array($attribute, PermissionEnum::values(), true)) {
            return false;
        }

        if (!$subject instanceof TimesheetComment) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token, ?Vote $vote = null): bool
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            return false;
        }

        /** @var TimesheetComment $comment */
        $comment = $subject;

        return match ($attribute) {
            PermissionEnum::CAN_ADD_TIMESHEET_COMMENT->value => $this->canAddComment($comment, $user, $vote),
            default => throw new \LogicException('This code should not be reached!'),
        };
    }

    private function canAddComment(TimesheetComment $comment, User $user, ?Vote $vote): bool
    {
        if (!$comment->getTimesheet()?->isOwner($user) && !$user->getSubordinates()->contains($comment->getTimesheet()?->getEmployee())) {
            $vote?->addReason(sprintf(
                ErrorMessageEnum::TS_COMMENT_ADD_NOT_AUTHORIZED->value,
                $user->getUserIdentifier()
            ));

            return false;
        }

        return true;
    }
}
