<?php

namespace App\Infrastructure\Profesor;

interface ProfesorReaderRepositoryInterface
{
    public function getProfesorList(): array;
}
