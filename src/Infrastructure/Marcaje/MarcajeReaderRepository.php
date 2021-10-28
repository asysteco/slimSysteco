<?php

namespace App\Infrastructure\Marcaje;

use App\Domain\Fichaje\FichajeFactory;
use DateTime;
use App\Infrastructure\PDO\PdoDataAccess;
use App\Infrastructure\Marcaje\MarcajeReaderRepositoryInterface;

class MarcajeReaderRepository implements MarcajeReaderRepositoryInterface
{
    private PdoDataAccess $pdo;

    public function __construct(PdoDataAccess $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getFichajes(DateTime $date): array
    {
        $sql = "SELECT DISTINCT f.*, p.Nombre as Profesor
            FROM Fichar f
                INNER JOIN Profesores p ON p.ID = f.ID_PROFESOR
            WHERE f.Fecha = :date
            ORDER BY f.Fecha DESC, f.F_entrada DESC";

        $params = [
            ':date' => $date->format('Y-m-d')
        ];

        $result = $this->pdo->query($sql, $params);

        return FichajeFactory::createFromResultSet($result);
    }

    public function getFaltas(DateTime $date): array
    {
        $sql = "SELECT DISTINCT p.Nombre as Profesor, :date as Fecha, d.Diasemana as DIA_SEMANA
            FROM Profesores p
                LEFT JOIN Marcajes m ON p.ID = m.ID_PROFESOR AND m.Fecha = :date
                LEFT JOIN Diasemana d ON d.ID = :weekDay
            WHERE p.ID not IN (SELECT f.ID FROM Fichar f WHERE f.Fecha = :date)
            ORDER BY p.Nombre";

        $params = [
            ':date' => $date->format('Y-m-d'),
            ':weekDay' => $date->format('N')
        ];

        $result = $this->pdo->query($sql, $params);

        return FichajeFactory::createFromResultSet($result);
    }
}
