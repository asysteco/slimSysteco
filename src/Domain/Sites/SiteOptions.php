<?php

declare(strict_types=1);

namespace App\Domain\Sites;

use JsonSerializable;

class SiteOptions implements JsonSerializable
{
    private ?int $dailyQr;
    private ?int $googleQr;
    private ?int $ficharSalida;
    private ?int $qrReader;
    private ?int $edificios;
    private ?int $autoScroll;

    public function __construct(
        ?int $dailyQr = null,
        ?int $googleQr = null,
        ?int $ficharSalida = null,
        ?int $qrReader = null,
        ?int $edificios = null,
        ?int $autoScroll = null
    ) {
        $this->dailyQr = $dailyQr;
        $this->googleQr = $googleQr;
        $this->ficharSalida = $ficharSalida;
        $this->qrReader = $qrReader;
        $this->edificios = $edificios;
        $this->autoScroll = $autoScroll;
    }

    public function jsonSerialize(): array
    {
        return [
            'dailyQr' => $this->dailyQr,
            'googleQr' => $this->googleQr,
            'ficharSalida' => $this->ficharSalida,
            'qrReader' => $this->qrReader,
            'edificios' => $this->edificios,
            'autoScroll' => $this->autoScroll
        ];
    }
}