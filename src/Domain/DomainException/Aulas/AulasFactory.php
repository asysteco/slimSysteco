<?php

namespace App\Domain\Aulas;

use App\Domain\Aulas\Aulas;

class AulasFactory
{
    public static function create(array $aula): Aulas {
        $id = $aula['ID'] ?? null;
        $name = $aula['Nombre'] ?? '';

        return new Aulas(
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
