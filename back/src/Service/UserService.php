<?php

namespace App\Service;

use App\Dto\Entity\CategorizedEmployees;
use App\Entity\User;
use App\Repository\UserRepository;

readonly class UserService
{
    public function __construct(private UserRepository $employeeRepository)
    {
    }

    public function getById(int $id): ?User
    {
        return $this->employeeRepository->findOneBy(['id' => $id]);
    }

    public function getEmployeesByUuids(array $uuids): array
    {
        return $this->employeeRepository->findByUuids($uuids);
    }

    public function categorizeByStatus(array $givenUuids): CategorizedEmployees
    {
        $categorizedEmployees = new CategorizedEmployees();

        $employeesByUuid = $this->getEmployeesByUuids($givenUuids);

        $foundedUuids = [];

        /** @var User $employee */
        foreach ($employeesByUuid as $employee) {
            $foundedUuids[] = $employee->getUuid()->toRfc4122();
            if ($employee->getManager()) {
                $categorizedEmployees->addAlreadyLinked($employee);
                continue;
            }

            $categorizedEmployees->addOrphans($employee);
        }

        $notFoundUuids = array_diff($givenUuids, $foundedUuids);

        foreach ($notFoundUuids as $notFoundUuid) {
            $categorizedEmployees->addNotFound($notFoundUuid);
        }

        return $categorizedEmployees;
    }
}
