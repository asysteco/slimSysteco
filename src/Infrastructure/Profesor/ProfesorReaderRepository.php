<?php

namespace App\Infrastructure\Profesor;

use App\Domain\Profesor\Profesor;
use App\Domain\Profesor\ProfesorFactory;
use App\Infrastructure\PDO\PdoDataAccess;
use App\Infrastructure\Profesor\ProfesorReaderRepositoryInterface;

class ProfesorReaderRepository implements ProfesorReaderRepositoryInterface
{
    private PdoDataAccess $pdo;

    public function __construct(PdoDataAccess $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getUserById(int $id): Profesor
    {
        $sql = "SELECT ID, Iniciales, Nombre, TIPO, Tutor, Activo, Sustituido, Status
            FROM Profesores
            WHERE ID = :id";

        $params = [':id' => $id];

        $result = $this->pdo->query($sql, $params, true);

        return ProfesorFactory::create($result);
    }

    public function getProfesorList(): array
    {
        $sql = "SELECT ID, Iniciales, Nombre, TIPO, Tutor, Activo, Sustituido, Status
            FROM Profesores
            WHERE TIPO != 1";

        $result = $this->pdo->query($sql);

        return ProfesorFactory::createFromResultSet($result);
    }
}
