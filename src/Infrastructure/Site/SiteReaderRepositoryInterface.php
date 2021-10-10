<?php

namespace App\Infrastructure\Site;

use App\Domain\Sites\Site;

interface SiteReaderRepositoryInterface
{
    public function getActiveSites(): array;
    public function getSiteInfoByName(string $siteName): Site;
    public function getSiteDbConfig(string $siteName): array;
}
