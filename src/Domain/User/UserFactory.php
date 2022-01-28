<?php

namespace App\Domain\User;

use App\Domain\User\User;

class UserFactory
{
    public static function create(array $user): User {
        $id = $user['ID'] ?? null;
        $name = $user['Nombre'] ?? null;
        $username = $user['Iniciales'] ?? null;
        $type = $user['TIPO'] ?? null;

        return new User(
            $id,
            $name,
            $username,
            $type
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
