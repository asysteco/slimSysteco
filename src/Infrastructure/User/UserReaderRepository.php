<?php

namespace App\Infrastructure\User;

use App\Domain\User\User;
use App\Domain\User\UserFactory;
use App\Infrastructure\PDO\PdoDataAccess;
use App\Infrastructure\User\UserReaderRepositoryInterface;

class UserReaderRepository implements UserReaderRepositoryInterface
{
    private PdoDataAccess $pdo;

    public function __construct(PdoDataAccess $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getUserLogin(string $username, string $password): ?User
    {
        $sql = "SELECT ID, Iniciales, Nombre FROM Profesores WHERE Iniciales = :username AND Password = :password";

        $params = [
            ':username' => $username,
            ':password' => $password
        ];

        $result = $this->pdo->query($sql, $params, true);

        if (!empty($result)) {
            return UserFactory::create($result);
        }

        return null;
    }
}
