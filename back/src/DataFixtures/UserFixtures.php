<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public const string USER_REFERENCE = 'user';
    public const string MANAGER_REFERENCE = 'manager';

    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        $managerUser = new User();
        $managerUser->setEmail('manager@example.com');
        $managerUser->setFirstName('Manager');
        $managerUser->setLastName('Boss');
        $managerUser->setPassword($this->hasher->hashPassword($managerUser, 'password123'));
        $managerUser->setRoles(['ROLE_MANAGER']);
        $manager->persist($managerUser);
        $this->addReference(self::MANAGER_REFERENCE, $managerUser);

        $user = new User();
        $user->setEmail('user@example.com');
        $user->setFirstName('John');
        $user->setLastName('Doe');
        $user->setPassword($this->hasher->hashPassword($user, 'password123'));
        $user->setManager($managerUser);
        $manager->persist($user);
        $this->addReference(self::USER_REFERENCE, $user);

        $user = new User();
        $user->setEmail('link1@example.com');
        $user->setFirstName('John');
        $user->setLastName('Doe');
        $user->setPassword($this->hasher->hashPassword($user, 'password123'));
        $manager->persist($user);

        $user = new User();
        $user->setEmail('link2@example.com');
        $user->setFirstName('John');
        $user->setLastName('Doe');
        $user->setPassword($this->hasher->hashPassword($user, 'password123'));
        $manager->persist($user);

        $manager->flush();
    }
}
