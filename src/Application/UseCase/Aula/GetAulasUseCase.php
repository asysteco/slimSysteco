<?php

namespace App\Application\UseCase\Aula;

use App\Infrastructure\Aula\AulaReaderRepositoryInterface;

class GetAulasUseCase
{
    private AulaReaderRepositoryInterface $aulaReaderRepository;

    public function __construct(AulaReaderRepositoryInterface $aulaReaderRepository)
    {
        $this->aulaReaderRepository = $aulaReaderRepository;
    }

    public function execute(): array
    {
        return $this->aulaReaderRepository->getAulas();
    }
}
