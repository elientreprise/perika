<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Dto\Response\LinkEmployeeResponse;
use App\Entity\User;
use App\Service\UserService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

readonly class LinkEmployeeProcessor implements ProcessorInterface
{
    public function __construct(
        #[Autowire(service: 'api_platform.doctrine.orm.state.persist_processor')]
        private ProcessorInterface $persistProcessor,
        private UserService $employeeService,
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = [])
    {
        if (!$data instanceof User) {
            return $data;
        }

        $employeeUuids = $data->employeeUuids ?? [];
        $categorizedEmployees = $this->employeeService->categorizeByStatus($employeeUuids);

        foreach ($categorizedEmployees->getOrphans() as $employee) {
            $data->addSubordinate($employee);
        }

        $this->entityManager->flush();

        return new LinkEmployeeResponse(
            status: $categorizedEmployees->countOrphans() === count($employeeUuids)
                ? 'success'
                : ($categorizedEmployees->countOrphans() > 0 ? 'partial_success' : 'error'),
            summary: [
                'added' => $categorizedEmployees->countOrphans(),
                'notFounds' => $categorizedEmployees->countNotFounds(),
                'alreadyLinked' => $categorizedEmployees->countAlreadyLinked(),
            ],
            details: [
                'added' => $categorizedEmployees->getOrphans()->toArray(),
                'notFound' => $categorizedEmployees->getNotFounds()->toArray(),
                'alreadyLinked' => $categorizedEmployees->getAlreadyLinked()->toArray(),
            ],
            message: sprintf(
                '%d ajoutés, %d introuvables, %d déjà reliés.',
                $categorizedEmployees->countOrphans(), $categorizedEmployees->countNotFounds(), $categorizedEmployees->countAlreadyLinked()
            ),
            refreshManagerData: $categorizedEmployees->countOrphans() > 0,
        );
    }
}
