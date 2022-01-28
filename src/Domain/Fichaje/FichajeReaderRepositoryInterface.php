<?php

namespace App\Domain\Fichaje;

interface FichajeReaderRepositoryInterface
{
    public function getHourByTime(string $time): int;
}