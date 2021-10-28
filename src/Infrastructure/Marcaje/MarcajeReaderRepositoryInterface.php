<?php

namespace App\Infrastructure\Marcaje;

use DateTime;

interface MarcajeReaderRepositoryInterface
{
    public function getFichajes(DateTime $date): array;
    public function getFaltas(DateTime $date): array;
}
