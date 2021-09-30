<?php

namespace App\Infrastructure\Site;

use App\Domain\Sites\SiteFactory;
use App\Infrastructure\PDO\PdoDataAccess;
use App\Infrastructure\Site\SiteReaderRepositoryInterface;

class SiteReaderRepository implements SiteReaderRepositoryInterface
{
    private PdoDataAccess $pdo;

    public function __construct(PdoDataAccess $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getActiveSites(): array
    {
        $sites = [];
        $sql = "SELECT ID, Nombre FROM Centros WHERE Activo = :active";
        $params = [
            ':active' => 1
        ];

        $result = $this->pdo->query($sql, $params);

        if (!empty($result)) {
            $sitesResponse = SiteFactory::createFromResultSet($result);

            $sites = array_map(
                static function ($site) {
                    return $site->name();
                },
                $sitesResponse
            );
        }

        return $sites;
    }
}