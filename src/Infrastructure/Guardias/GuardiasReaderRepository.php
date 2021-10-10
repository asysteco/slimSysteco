<?php

namespace App\Infrastructure\Guardias;

use App\Infrastructure\PDO\PdoDataAccess;

class GuardiasReaderRepository implements GuardiasReaderRepositoryInterface
{
    private PdoDataAccess $pdo;

    public function __construct(PdoDataAccess $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getGuardias(): array
    {
        $sql = "SELECT 
            p.Nombre,
            A.Nombre as Aula,
            GROUP_CONCAT(C.Nombre SEPARATOR ', ') as Grupos,
            h.Edificio,
            h.Hora,
            hs.Inicio,
            hs.Fin,
            h.Tipo
        FROM Marcajes m
            INNER JOIN Horarios h ON m.ID_PROFESOR = h.ID_PROFESOR AND m.Hora = h.Hora AND m.Dia = h.Dia
            INNER JOIN Profesores p ON m.ID_PROFESOR = p.ID AND h.ID_PROFESOR = p.ID
            INNER JOIN Horas hs ON h.Hora = hs.Hora AND m.Hora = hs.Hora AND m.Tipo = hs.Tipo AND h.Tipo = hs.Tipo
            INNER JOIN Aulas A ON h.Aula = A.ID
            INNER JOIN Cursos C ON h.Grupo = C.ID
        WHERE (m.Asiste = 0 OR m.Asiste = 2)
            AND p.Activo=1
            AND p.Sustituido=0
            AND p.TIPO <> 1
            AND m.Fecha = :currentDay
            AND hs.Fin > :currentTime
        GROUP BY p.nombre, A.Nombre, h.Hora
        ORDER BY m.Hora ASC, h.Edificio ASC, p.Nombre ASC";

        $params = [
            ':currentDay' => date('Y-m-d'),
            ':currentTime' => date('H:i:s')
        ];

        return $this->pdo->query($sql, $params);
    }
}
