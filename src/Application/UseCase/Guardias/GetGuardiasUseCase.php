<?php

namespace App\Application\UseCase\Guardias;

use App\Infrastructure\Guardias\GuardiasReaderRepositoryInterface;

class GetGuardiasUseCase
{
    private GuardiasReaderRepositoryInterface $guardiasReaderRepository;

    public function __construct(GuardiasReaderRepositoryInterface $guardiasReaderRepository)
    {
        $this->guardiasReaderRepository = $guardiasReaderRepository;
    }

    public function execute(): array
    {
        return $this->guardiasReaderRepository->getGuardias();
    }
}
