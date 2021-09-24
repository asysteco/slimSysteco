<?php

namespace App\Domain\User;

use App\Domain\User\User;

class UserFactory
{
    public static function create(array $site): User {
        $id = $site['ID'] ?? null;
        $name = $site['Nombre'] ?? '';
        $username = $site['Iniciales'] ?? '';

        return new User(
            $id,
            $name,
            $username
        );
    }

    public static function createFromResultSet(array $resultSet): array
    {
        return array_map(
            function ($user) {
                return self::create($user);
            },
            $resultSet
        );
    }
}
