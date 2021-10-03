<?php

namespace App\Infrastructure\Aula;

use App\Domain\Aula\AulaFactory;
use App\Infrastructure\PDO\PdoDataAccess;
use App\Infrastructure\Aula\AulaReaderRepositoryInterface;

class AulaReaderRepository implements AulaReaderRepositoryInterface
{
    private PdoDataAccess $pdo;

    public function __construct(PdoDataAccess $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAulas(): array
    {
        $sql = "SELECT ID, Nombre FROM Aulas ORDER BY Nombre";

        $result = $this->pdo->query($sql);

        return AulaFactory::createFromResultSet($result);
    }
}
