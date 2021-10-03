<?php

namespace App\Infrastructure\Horas;

interface HorasReaderRepositoryInterface
{
    public function getFranjas(): array;
}
