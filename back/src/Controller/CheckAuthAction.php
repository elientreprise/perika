<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;

class CheckAuthAction extends AbstractController
{
    public function __invoke(Security $security): JsonResponse
    {
        return $this->json(['user' => $this->getUser()], 200, [], ['groups' => [
            'employee:read',
        ]]);
    }
}
