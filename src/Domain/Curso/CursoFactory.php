<?php

namespace App\Domain\Curso;

use App\Domain\Curso\Curso;

class CursoFactory
{
    public static function create(array $aula): Curso {
        $id = $aula['ID'] ?? null;
        $name = $aula['Nombre'] ?? '';

        return new Curso(
            $id,
            $name
        );
    }

    public static function createFromResultSet(array $resultSet): array
    {
        return array_map(
            function ($aula) {
                return self::create($aula);
            },
            $resultSet
        );
    }
}
