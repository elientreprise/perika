<?php

namespace App\Security;

use App\Entity\Timesheet;
use App\Entity\User;
use App\Enum\PermissionEnum;
use App\Enum\ResponseMessage\ErrorMessageEnum;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Vote;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class TimesheetVoter extends Voter
{
    protected function supports(string $attribute, mixed $subject): bool
    {
        if (!in_array($attribute, PermissionEnum::values(), true)) {
            return false;
        }

        if (!$subject instanceof Timesheet) {
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

        /** @var Timesheet $timesheet */
        $timesheet = $subject;

        return match ($attribute) {
            PermissionEnum::CAN_CREATE_TIMESHEET->value => $this->canCreate($timesheet, $user, $vote),
            PermissionEnum::CAN_VIEW_TIMESHEET->value => $this->canView($timesheet, $user, $vote),
            PermissionEnum::CAN_EDIT_TIMESHEET->value => $this->canEdit($timesheet, $user, $vote),
            PermissionEnum::CAN_VIEW_TIMESHEET_COMMENT_COLLECTION->value => $this->canViewCommentCollection($timesheet, $user, $vote),
            default => throw new \LogicException('This code should not be reached!'),
        };
    }

    private function canCreate(Timesheet $timesheet, User $user, ?Vote $vote): bool
    {
        if (!$timesheet->isOwner($user) && !$user->getSubordinates()->contains($timesheet->getEmployee())) {
            $vote?->addReason(sprintf(
                ErrorMessageEnum::TS_CREATION_NOT_AUTHORIZED->value,
                $user->getUserIdentifier()
            ));

            return false;
        }

        return true;
    }

    private function canEdit(Timesheet $timesheet, User $user, ?Vote $vote): bool
    {
        if (!$timesheet->isOwner($user)) {
            $vote?->addReason(sprintf(
                ErrorMessageEnum::TS_EDITION_NOT_AUTHORIZED->value,
                $user->getUserIdentifier()
            ));

            return false;
        }

        return true;
    }

    private function canView(Timesheet $timesheet, User $user, ?Vote $vote): bool
    {
        if (
            !$user->isRh()
            && !$user->isManager()
            && !$user->getSubordinates()->contains($timesheet->getEmployee())
            && !$timesheet->isOwner($user)
        ) {
            $vote?->addReason(sprintf(
                'The logged in user (username: %s) can\'t access to this resource. Only HR, Manager or user him self can do this.',
                $user->getUserIdentifier()
            ));

            return false;
        }

        return true;
    }

    private function canViewCommentCollection(Timesheet $timesheet, User $user, ?Vote $vote): bool
    {
        if (
            !$this->canView($timesheet, $user, $vote)
        ) {
            return false;
        }

        return true;
    }
}
