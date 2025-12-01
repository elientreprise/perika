<?php

namespace App\Security;

use App\Entity\User;
use App\Enum\PermissionEnum;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Vote;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class EmployeeVoter extends Voter
{
    protected function supports(string $attribute, mixed $subject): bool
    {
        if (!in_array($attribute, PermissionEnum::values(), true)) {
            return false;
        }

        if (!$subject instanceof User) {
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

        /** @var User $employee */
        $employee = $subject;

        return match ($attribute) {
            PermissionEnum::CAN_EDIT_EMPLOYEE->value => $this->canEdit($employee, $user, $vote),
            PermissionEnum::CAN_VIEW_EMPLOYEE->value => $this->canView($employee, $user, $vote),
            PermissionEnum::CAN_ADD_SUBORDINATES->value => $this->canAddSubordinate($employee, $user, $vote),
            PermissionEnum::CAN_VIEW_TIMESHEET_COLLECTION->value => $this->canViewTimesheetCollection($employee, $user, $vote),
            default => throw new \LogicException('This code should not be reached!'),
        };
    }

    private function canEdit(User $employee, User $user, ?Vote $vote): bool
    {
        if (
            !$this->canView($employee, $user, $vote)
        ) {
            return false;
        }

        return true;
    }

    private function canView(User $employee, User $user, ?Vote $vote): bool
    {
        if (
            !$user->isRh()
            && !$user->isManager()
            && !$user->getSubordinates()->contains($employee)
            && $user !== $employee
        ) {
            $vote?->addReason(sprintf(
                'The logged in user (username: %s) can\'t access to this resource. Only HR, Manager or user him self can do this.',
                $user->getUserIdentifier()
            ));

            return false;
        }

        return true;
    }
    private function canViewTimesheetCollection(User $employee, User $user, ?Vote $vote): bool
    {
        if (
            !$this->canView($employee, $user, $vote)

        ) {
            return false;
        }

        return true;
    }

    private function canAddSubordinate(User $employee, User $user, ?Vote $vote): bool
    {
        // todo ajouter la logique
        return true;
    }
}
