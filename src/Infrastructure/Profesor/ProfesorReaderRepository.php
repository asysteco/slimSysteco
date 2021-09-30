<?php

namespace App\Infrastructure\Profesor;

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

    public function getProfesorList(): array
    {
        $sql = "SELECT ID, Iniciales, Nombre, TIPO, Tutor, Activo, Sustituido, Status
            FROM Profesores
            WHERE TIPO != 1";

        $result = $this->pdo->query($sql);

        if (!empty($result)) {
            return ProfesorFactory::createFromResultSet($result);
        }

        return [];
    }
}
