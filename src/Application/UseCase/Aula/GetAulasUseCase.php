<?php

namespace App\Application\UseCase\Aula;

use App\Infrastructure\Aulas\AulasReaderRepositoryInterface;

class GetAulasUseCase
{
    private AulasReaderRepositoryInterface $aulasReaderRepository;

    public function __construct(AulasReaderRepositoryInterface $aulasReaderRepository)
    {
        $this->aulasReaderRepository = $aulasReaderRepository;
    }

    public function execute(): array
    {
        return $this->aulasReaderRepository->getAulas();
    }
}
