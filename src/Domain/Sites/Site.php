<?php

declare(strict_types=1);

namespace App\Domain\Sites;

use JsonSerializable;

class Site implements JsonSerializable
{
    private int $id;
    private string $name;
    private ?SiteOptions $options;

    public function __construct(
        int $id,
        string $name,
        ?SiteOptions $options = null
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->options = $options;
    }

    public function id(): int
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function options(): string
    {
        return $this->options;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'options' => $this->options
        ];
    }
}
