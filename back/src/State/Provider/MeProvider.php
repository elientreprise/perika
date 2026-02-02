<?php

namespace App\State\Provider;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Entity\User;
use App\Service\UserService;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;

readonly class MeProvider implements ProviderInterface
{
    public function __construct(
        private Security $security,
        private UserService $employeeService,
    ) {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        /** @var User $user */
        $user = $this->security->getUser();

        $employee = $this->employeeService->getById($user->getId());

        if (!$employee) {
            return new JsonResponse(['error' => 'Unauthorized'], 401);
        }

        return $employee;
    }
}
