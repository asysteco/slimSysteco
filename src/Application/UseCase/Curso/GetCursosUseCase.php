<?php

namespace App\Application\UseCase\Curso;

use App\Infrastructure\Curso\CursoReaderRepositoryInterface;

class GetCursosUseCase
{
    private CursoReaderRepositoryInterface $cursoReaderRepository;

    public function __construct(CursoReaderRepositoryInterface $cursoReaderRepository)
    {
        $this->cursoReaderRepository = $cursoReaderRepository;
    }

    public function execute(): array
    {
        return $this->cursoReaderRepository->getCursos();
    }
}
