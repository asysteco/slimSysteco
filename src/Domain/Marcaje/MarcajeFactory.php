<?php

namespace App\Domain\Marcaje;

use App\Domain\Marcaje\Marcaje;

class MarcajeFactory
{
    public static function create(array $asistencia): Marcaje {
        $id = $asistencia['ID'] ?? null;
        $name = $asistencia['Nombre'] ?? '';

        return new Marcaje(
            $id,
            $name
        );
    }

    public static function createFromResultSet(array $resultSet): array
    {
        return array_map(
            function ($asistencia) {
                return self::create($asistencia);
            },
            $resultSet
        );
    }
}
