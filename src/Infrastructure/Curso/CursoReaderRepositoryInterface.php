<?php

namespace App\Infrastructure\Curso;

interface CursoReaderRepositoryInterface
{
    public function getCursos(): array;
}
