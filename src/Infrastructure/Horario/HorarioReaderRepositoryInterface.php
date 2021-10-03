<?php

namespace App\Infrastructure\Horario;

interface HorarioReaderRepositoryInterface
{
    public function horarioExists(?int $userId = null): bool;
}
