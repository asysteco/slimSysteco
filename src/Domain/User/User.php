<?php

declare(strict_types=1);

namespace App\Domain\User;

use JsonSerializable;

class User implements JsonSerializable
{
    private int $id;
    private string $username;
    private string $name;
    private int $type;

    public function __construct(
        int $id,
        string $username,
        string $name,
        int $type
    ) {
        $this->id = $id;
        $this->username = $username;
        $this->name = $name;
        $this->type = $type;
    }

    public function id(): int
    {
        return $this->id;
    }

    public function username(): string
    {
        return $this->username;
    }

    public function name(): string
    {
        return $this->username;
    }

    public function type(): int
    {
        return $this->type;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'username' => $this->username,
            'name' => $this->name,
            'type' => $this->type
        ];
    }
}
