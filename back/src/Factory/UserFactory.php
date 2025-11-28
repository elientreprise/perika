<?php

namespace App\Factory;

use App\Entity\User;
use Zenstruck\Foundry\Persistence\PersistentObjectFactory;

/**
 * @extends PersistentObjectFactory<User>
 */
final class UserFactory extends PersistentObjectFactory
{
    #[\Override]
    public static function class(): string
    {
        return User::class;
    }

    #[\Override]
    protected function defaults(): array|callable
    {
        return [
            'email' => self::faker()->unique()->email(),
            'isActive' => self::faker()->boolean(),
            'password' => '$2y$13$X4.ZXmiCq2CXGeeKx.TFQuz0oWysNP4p3E7Mru4GW8Ot80CaTfury', // => test
            'manager' => null,
        ];
    }
}
