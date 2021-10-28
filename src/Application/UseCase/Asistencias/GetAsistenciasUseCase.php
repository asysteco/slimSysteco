<?php

namespace App\Application\UseCase\Asistencias;

use DateTime;
use App\Infrastructure\Marcaje\MarcajeReaderRepositoryInterface;
use App\Application\UseCase\Asistencias\Exception\InvalidDateFormatException;

class GetAsistenciasUseCase
{
    private MarcajeReaderRepositoryInterface $marcajeReaderRepository;

    public function __construct(MarcajeReaderRepositoryInterface $marcajeReaderRepository)
    {
        $this->marcajeReaderRepository = $marcajeReaderRepository;
    }

    public function execute(?string $date = null): array
    {
        $date = $this->validateDate($date);
        $fichajes = $this->marcajeReaderRepository->getFichajes($date);
        $faltas = $this->marcajeReaderRepository->getFaltas($date);

        return [
            'fichajes' => $fichajes,
            'faltas' => $faltas
        ];
    }

    private function validateDate(?string $date)
    {
        if ($date === null) {
            return new DateTime();
        }

        $validDate = DateTime::createFromFormat('d/m/Y', $date);

        if ($validDate === false) {
            throw new InvalidDateFormatException();
        }

        return $validDate;
    }
}
