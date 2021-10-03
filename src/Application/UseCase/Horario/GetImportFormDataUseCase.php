<?php

namespace App\Application\UseCase\Horario;

use App\Infrastructure\Horario\HorarioReaderRepositoryInterface;
use App\Infrastructure\Horas\HorasReaderRepositoryInterface;
use App\Infrastructure\Site\SiteReaderRepositoryInterface;

class GetImportFormDataUseCase
{
    private SiteReaderRepositoryInterface $siteReaderRepository;
    private HorasReaderRepositoryInterface $horasReaderRepository;
    private HorarioReaderRepositoryInterface $horarioReaderRepository;

    public function __construct(
        SiteReaderRepositoryInterface $siteReaderRepository,
        HorasReaderRepositoryInterface $horasReaderRepository,
        HorarioReaderRepositoryInterface $horarioReaderRepository
    ) {
        $this->siteReaderRepository = $siteReaderRepository;
        $this->horasReaderRepository = $horasReaderRepository;
        $this->horarioReaderRepository = $horarioReaderRepository;
    }

    public function execute(string $siteName): array
    {
        $siteInfo = $this->siteReaderRepository->getSiteInfoByName($siteName);
        $franjas = $this->horasReaderRepository->getFranjas();
        $horariosExists = $this->horarioReaderRepository->horarioExists();

        return [
            'siteInfo' => $siteInfo->options(),
            'franjas' => $franjas,
            'horariosExists' => $horariosExists
        ];
    }
}
