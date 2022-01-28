<?php

namespace App\Infrastructure\Fichaje;

use App\Infrastructure\PDO\PdoDataAccess;
use App\Domain\Fichaje\FichajeReaderRepositoryInterface;

class FichajeReaderRepository implements FichajeReaderRepositoryInterface
{
    private PdoDataAccess $pdo;

    public function __construct(PdoDataAccess $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getHourByTime(string $time): int
    {
        $sql = "SELECT Hora FROM Horas WHERE Fin >= :finishHour LIMIT 1";
        $params = [
            ':finishHour' => $time
        ];

        $result = $this->pdo->query($sql, $params, true);

        return $result['Hora'];
    }
}
