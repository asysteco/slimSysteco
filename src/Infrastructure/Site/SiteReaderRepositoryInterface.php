<?php

namespace App\Infrastructure\Site;

interface SiteReaderRepositoryInterface
{
    public function getActiveSites(): array;
}
