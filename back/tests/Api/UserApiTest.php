<?php

namespace App\Tests\Api;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use ApiPlatform\Symfony\Bundle\Test\Client;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Tests\Interface\LoggedApiTestInterface;
use Symfony\Component\Uid\Uuid;

class UserApiTest extends ApiTestCase implements LoggedApiTestInterface
{
    private static Client $client;

    public static function setUpBeforeClass(): void
    {
        self::$client = static::createClient(['cookie' => true]);
        static::$alwaysBootKernel = true;
    }

    public function testRegisterUser(): void
    {
        $response = self::$client->request('POST', '/api/register', [
            'json' => [
                'email' => 'test@example.com',
                'password' => 'password123',
                'firstName' => 'John',
                'lastName' => 'Doe',
            ],
            'headers' => [
                'Content-type' => 'application/ld+json',
            ],
        ]);

        $this->assertResponseIsSuccessful();
        $this->assertJsonContains(['email' => 'test@example.com']);
    }

    public function testLoginUser(): void
    {
        self::$client->request('POST', '/api/login', [
            'json' => ['email' => 'test@example.com', 'password' => 'password123'],
        ]);

        $this->assertResponseIsSuccessful();
        $this->assertJsonContains(
            [
                'message' => 'Connexion réussie ✅',
                'user' => [
                    'email' => 'test@example.com',
                    'roles' => [
                        'ROLE_USER',
                    ],
                ],
            ]
        );
    }

    public function testGetMe(): void
    {
        self::$client->request('GET', '/api/me');

        $this->assertResponseIsSuccessful();
        $this->assertJsonContains(['email' => 'test@example.com']);
    }

    public function testIsAuthenticated(): void
    {
        self::bootKernel();

        $userRepository = self::getContainer()->get(UserRepository::class);

        /** @var User $employee */
        $employee = $userRepository->findOneBy(['email' => 'test@example.com']);

        self::$client->request('GET', '/api/authenticated');

        $this->assertResponseIsSuccessful();
        $this->assertJsonContains([
            'user' => [
                'uuid' => $employee->getUuid()->toRfc4122(),
                'email' => $employee->getEmail(),
                'firstName' => $employee->getFirstName(),
                'lastName' => $employee->getLastName(),
                'position' => $employee->getPosition(),
                'salary' => $employee->getSalary(),
                'hire_date' => $employee->getHireDate(),
                'phoneNumber' => $employee->getPhoneNumber(),
                'birthDate' => $employee->getBirthDate(),
                'manager' => $employee->getManager(),
                'fullName' => $employee->getFullName(),
                'seniority' => $employee->getSeniority(),
            ],
        ]);
    }

    public function testIsNotAuthenticated(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/authenticated');

        $this->assertResponseIsSuccessful();
        $this->assertJsonContains(['user' => null]);
    }

    public function testProtectedRouteWithoutAuth(): void
    {
        $uuid = Uuid::v7();
        $client = static::createClient();
        $client->request('GET', sprintf('/api/employees/%s', $uuid->toRfc4122()));

        $this->assertResponseStatusCodeSame(401);
    }
}
