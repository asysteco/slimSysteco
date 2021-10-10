<?php

namespace App\Infrastructure\Guardias;

interface GuardiasReaderRepositoryInterface
{
    public function getGuardias(): array;
}
