<?php

namespace App\Infrastructure\Profesor;

use App\Domain\Profesor\Profesor;

interface ProfesorReaderRepositoryInterface
{
    public function getUserById(int $id): Profesor;
    public function getProfesorList(): array;
}
