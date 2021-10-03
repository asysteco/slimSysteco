<?php

declare(strict_types=1);

namespace App\Domain\Aulas;

use JsonSerializable;

class Aulas implements JsonSerializable
{
    private int $id;
    private string $name;

    public function __construct(
        int $id,
        string $name
    ) {
        $this->id = $id;
        $this->name = $name;
    }

    public function id(): int
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name
        ];
    }
}
