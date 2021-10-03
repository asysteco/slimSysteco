<?php

namespace App\Domain\Aula;

use App\Domain\Aula\Aula;

class AulaFactory
{
    public static function create(array $aula): Aula {
        $id = $aula['ID'] ?? null;
        $name = $aula['Nombre'] ?? '';

        return new Aula(
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
