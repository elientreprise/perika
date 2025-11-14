<?php

namespace App\Tests\Api;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use ApiPlatform\Symfony\Bundle\Test\Client;
use App\Repository\UserRepository;
use App\Tests\Interface\LoggedApiTestInterface;

class ManagerApiTest extends ApiTestCase implements LoggedApiTestInterface
{
    private static Client $client;

    public static function setUpBeforeClass(): void
    {
        self::$client = static::createClient(['cookie' => true]);
        static::$alwaysBootKernel = true;
    }

    public function testLoginUser(): void
    {
        self::$client->request('POST', '/api/login', [
            'json' => ['email' => 'manager@example.com', 'password' => 'password123'],
        ]);

        $this->assertJsonContains(
            [
                'message' => 'Connexion réussie ✅',
                'user' => [
                    'email' => 'manager@example.com',
                    'roles' => [
                        'ROLE_MANAGER',
                    ],
                ],
            ]
        );

        $this->assertResponseIsSuccessful();
    }

    public function testLinkEmployee(): void
    {
        self::bootKernel();

        $userRepository = self::getContainer()->get(UserRepository::class);

        $employees = $userRepository->findBy([], null, 2);
        $this->assertCount(2, $employees, 'Il faut au moins 2 employés pour le test');

        $employeeUuids = array_map(fn ($emp) => $emp->getUuid()->toRfc4122(), $employees);

        $manager = $userRepository->findOneBy(['email' => 'manager@example.com']);

        $response = self::$client->request(
            'PATCH',
            '/api/managers/'.$manager->getUuid()->toRfc4122().'/link-employee',
            [
                'json' => ['employeeUuids' => $employeeUuids],
                'headers' => ['Content-Type' => 'application/merge-patch+json'],
            ]
        );

        $this->assertResponseIsSuccessful();
        $this->assertJsonContains([
            'message' => '0 ajoutés, 2 introuvables, 0 déjà reliés.',
            'refreshManagerData' => false,
        ]);
    }
}
