<?php

namespace App\Infrastructure\Aulas;

interface AulasReaderRepositoryInterface
{
    public function getAulas(): array;
}
