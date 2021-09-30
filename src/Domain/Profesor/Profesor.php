<?php

declare(strict_types=1);

namespace App\Domain\Profesor;

class Profesor
{
    public const PERSONAL_TYPE = 3;

    private int $id;
    private string $username;
    private string $name;
    private int $type;
    private string $tutor;
    private bool $active;
    private bool $sustituted;
    private int $status;

    public function __construct(
        int $id,
        string $username,
        string $name,
        int $type,
        string $tutor,
        bool $active,
        bool $sustituted,
        int $status
    ) {
        $this->id = $id;
        $this->username = $username;
        $this->name = $name;
        $this->type = $type;
        $this->tutor = $tutor;
        $this->active = $active;
        $this->sustituted = $sustituted;
        $this->status = $status;
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
        return $this->name;
    }

    public function type(): int
    {
        return $this->type;
    }

    public function tutor(): string
    {
        return $this->tutor;
    }

    public function active(): bool
    {
        return $this->active;
    }

    public function sustituted(): bool
    {
        return $this->sustituted;
    }

    public function status(): int
    {
        return $this->status;
    }
}
