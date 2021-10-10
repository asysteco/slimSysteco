<?php

declare(strict_types=1);

namespace App\Domain\Sites;

use JsonSerializable;

class SiteOptions implements JsonSerializable
{
    private ?string $cryptKey;
    private ?int $dailyQr;
    private ?int $googleQr;
    private ?int $ficharSalida;
    private ?int $qrReader;
    private ?int $edificios;
    private ?int $autoScroll;

    public function __construct(
        ?string $cryptKey = null,
        ?int $dailyQr = null,
        ?int $googleQr = null,
        ?int $ficharSalida = null,
        ?int $qrReader = null,
        ?int $edificios = null,
        ?int $autoScroll = null
    ) {
        $this->cryptKey = $cryptKey;
        $this->dailyQr = $dailyQr;
        $this->googleQr = $googleQr;
        $this->ficharSalida = $ficharSalida;
        $this->qrReader = $qrReader;
        $this->edificios = $edificios;
        $this->autoScroll = $autoScroll;
    }

    public function cryptKey(): ?string
    {
        return $this->cryptKey;
    }

    public function dailyQr(): ?int
    {
        return $this->dailyQr;
    }

    public function googleQr(): ?int
    {
        return $this->googleQr;
    }

    public function ficharSalida(): ?int
    {
        return $this->ficharSalida;
    }

    public function qrReader(): ?int
    {
        return $this->qrReader;
    }

    public function edificios(): ?int
    {
        return $this->edificios;
    }

    public function autoScroll(): ?int
    {
        return $this->autoScroll;
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