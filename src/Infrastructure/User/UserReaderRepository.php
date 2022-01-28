<?php

namespace App\Infrastructure\User;

use App\Domain\Profesor\Profesor;
use App\Domain\Profesor\ProfesorFactory;
use App\Domain\User\User;
use App\Domain\User\UserFactory;
use App\Infrastructure\PDO\PdoDataAccess;
use App\Infrastructure\User\UserReaderRepositoryInterface;
use App\Infrastructure\Utility\DateMapper;

class UserReaderRepository implements UserReaderRepositoryInterface
{
    private const ADMIN_TYPE = 1;

    private PdoDataAccess $pdo;

    public function __construct(PdoDataAccess $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getUserLogin(string $username, string $password): ?User
    {
        $sql = "SELECT ID, Iniciales, Nombre, TIPO FROM Profesores WHERE Iniciales = :username AND Password = :password";

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

    public function getQrUserLogin(int $decriptedToken): bool
    {
        $sql = "SELECT EXISTS (
                SELECT * FROM Perfiles WHERE Tipo = :readerType AND ID = :token
            ) as logged";

        $params = [
            ':readerType' => 'reader',
            ':token' => $decriptedToken
        ];

        $result = $this->pdo->query($sql, $params, true);

        return isset($result['logged']) && $result['logged'] === '1';
    }

    public function getUserById(int $userId): ?Profesor
    {
        $sql = "SELECT ID, Iniciales, Nombre FROM Profesores WHERE ID = :id AND TIPO != :type";

        $params = [
            ':id' => $userId,
            ':type' => self::ADMIN_TYPE
        ];

        $result = $this->pdo->query($sql, $params, true);

        if (!empty($result)) {
            return ProfesorFactory::create($result);
        }

        return null;
    }

    public function getCheckOutTime(int $userId): string
    {
        $datosHoy = DateMapper::get();
        $diaSemana = $datosHoy['wday'];

        $sql = "SELECT h.Hora_salida as checkOut
            FROM Horarios h
                INNER JOIN Profesores p ON h.ID_PROFESOR=p.ID 
            WHERE p.ID = :id AND h.Dia = :diaSemana
            ORDER BY Hora_salida DESC
            LIMIT 1";

        $params = [
            ':id' => $userId,
            ':diaSemana' => $diaSemana
        ];

        $result = $this->pdo->query($sql, $params, true);

        return $result['checkOut'] ?? '00:00:00';
    }
}
