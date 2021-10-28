<?php

declare(strict_types=1);

namespace App\Domain\Fichaje;

use DateTime;
use JsonSerializable;
use App\Domain\Fichaje\Exception\InvalidDateFormatException;

class Fichaje implements JsonSerializable
{
    private ?int $id;
    private string $name;
    private DateTime $date;
    private ?string $checkIn;
    private ?string $checkOut;
    private ?string $weekDay;

    public function __construct(
        ?int $id,
        string $name,
        string $date,
        ?string $checkIn = null,
        ?string $checkOut = null,
        ?string $weekDay = null
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->setDate($date);
        $this->checkIn = $checkIn;
        $this->checkOut = $checkOut;
        $this->weekDay = $weekDay;
    }

    public function id(): ?int
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function date(): string
    {
        return $this->date->format('d-m-Y');
    }

    public function checkIn(): ?string
    {
        return $this->checkIn;
    }

    public function checkOut(): ?string
    {
        return $this->checkOut;
    }

    public function weekDay(): ?string
    {
        return $this->weekDay;
    }

    private function setDate(string $date): void
    {
        $newDate = new DateTime($date);

        if ($newDate === false) {
            throw new InvalidDateFormatException();
        }

        $this->date = $newDate;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'date' => $this->date,
            'checkIn' => $this->checkIn,
            'checkOut' => $this->checkOut,
            'weekDay' => $this->weekDay
        ];
    }
}
