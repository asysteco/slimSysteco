<?php

namespace App\Infrastructure\Aulas;

use App\Domain\Aulas\AulasFactory;
use App\Infrastructure\PDO\PdoDataAccess;
use App\Infrastructure\Aulas\AulasReaderRepositoryInterface;

class AulasReaderRepository implements AulasReaderRepositoryInterface
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

        return AulasFactory::createFromResultSet($result);
    }
}
