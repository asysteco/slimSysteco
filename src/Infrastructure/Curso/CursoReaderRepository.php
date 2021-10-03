<?php

namespace App\Infrastructure\Curso;

use App\Domain\Curso\CursoFactory;
use App\Infrastructure\PDO\PdoDataAccess;

class CursoReaderRepository implements CursoReaderRepositoryInterface
{
    private PdoDataAccess $pdo;

    public function __construct(PdoDataAccess $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getCursos(): array
    {
        $sql = "SELECT ID, Nombre FROM Cursos ORDER BY Nombre";

        $result = $this->pdo->query($sql);

        return CursoFactory::createFromResultSet($result);
    }
}
