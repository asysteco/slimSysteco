<?php

namespace App\Domain\User;

use App\Domain\User\User;

class UserFactory
{
    public static function create(array $user): User {
        $id = $user['ID'] ?? null;
        $name = $user['Nombre'] ?? '';
        $username = $user['Iniciales'] ?? '';

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
