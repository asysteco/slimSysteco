<?php

namespace App\Infrastructure\Horario;

use App\Infrastructure\PDO\PdoDataAccess;
use App\Infrastructure\Horario\HorarioReaderRepositoryInterface;

class HorarioReaderRepository implements HorarioReaderRepositoryInterface
{
    private PdoDataAccess $pdo;

    public function __construct(PdoDataAccess $pdo)
    {
        $this->pdo = $pdo;
    }

    public function horarioExists(?int $userId = null): bool
    {
        $params = [];
        $whereFilter = '';
        if ($userId !== null) {
            $params = [
                ':userId' => $userId
            ];
            $whereFilter = 'WHERE ID_PROFESOR = :userId';
        }

        $sql = sprintf("SELECT EXISTS(
            SELECT * FROM Horarios %s
        ) as horario", $whereFilter);

        $result = $this->pdo->query($sql, $params, true);

        return isset($result['horario']) ? $result['horario'] === '1' : false;
    }
}
