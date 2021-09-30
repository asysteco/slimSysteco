<?php

namespace App\Domain\Profesor;

use App\Domain\Profesor\Profesor;

class ProfesorFactory
{
    public static function create(array $profesor): Profesor {
        $id = $profesor['ID'] ?? null;
        $username = $profesor['Iniciales'] ?? '';
        $name = $profesor['Nombre'] ?? '';
        $type = $profesor['TIPO'] ?? Profesor::PERSONAL_TYPE;
        $tutor = $profesor['Tutor'] ?? '';
        $active = $profesor['Activo'] ?? false;
        $sustituted = $profesor['Sustituido'] ?? false;
        $status = $profesor['status'] ?? 0;

        return new Profesor(
            $id,
            $username,
            $name,
            $type,
            $tutor,
            $active,
            $sustituted,
            $status
        );
    }

    public static function createFromResultSet(array $resultSet): array
    {
        return array_map(
            function ($profesor) {
                return self::create($profesor);
            },
            $resultSet
        );
    }
}
