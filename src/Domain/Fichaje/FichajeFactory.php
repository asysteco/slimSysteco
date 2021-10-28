<?php

namespace App\Domain\Fichaje;

use App\Domain\Fichaje\Fichaje;

class FichajeFactory
{
    public static function create(array $fichaje): Fichaje {
        $id = $fichaje['ID'] ?? null;
        $name = $fichaje['Profesor'] ?? null;
        $date = $fichaje['Fecha'] ?? null;
        $checkIn = $fichaje['F_entrada'] ?? null;
        $checkOut = $fichaje['F_Salida'] ?? null;
        $weekDay = $fichaje['DIA_SEMANA'] ?? null;

        return new Fichaje(
            $id,
            $name,
            $date,
            $checkIn,
            $checkOut,
            $weekDay
        );
    }

    public static function createFromResultSet(array $resultSet): array
    {
        return array_map(
            function ($fichaje) {
                return self::create($fichaje);
            },
            $resultSet
        );
    }
}
