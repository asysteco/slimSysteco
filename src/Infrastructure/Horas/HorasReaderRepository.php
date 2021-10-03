<?php

namespace App\Infrastructure\Horas;

use App\Infrastructure\PDO\PdoDataAccess;
use App\Infrastructure\Horas\HorasReaderRepositoryInterface;

class HorasReaderRepository implements HorasReaderRepositoryInterface
{
    private PdoDataAccess $pdo;

    public function __construct(PdoDataAccess $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getFranjas(): array
    {
        $sql = "SELECT DISTINCT Tipo FROM Horas";

        return $this->pdo->query($sql);
    }
}
