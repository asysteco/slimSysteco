<?php

namespace App\Infrastructure\Site;

use App\Domain\Sites\Site;
use App\Domain\Sites\SiteFactory;
use App\Infrastructure\PDO\PdoDataAccess;
use App\Infrastructure\Site\SiteReaderRepositoryInterface;

class SiteReaderRepository implements SiteReaderRepositoryInterface
{
    private const ACTIVE_SITE_STATUS = 1;

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
            ':active' => self::ACTIVE_SITE_STATUS
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

    public function getSiteInfoByName(string $siteName): Site
    {
        $sql = "SELECT * FROM Centros WHERE Activo = :active AND Nombre = :siteName";
        $params = [
            ':active' => self::ACTIVE_SITE_STATUS,
            ':siteName' => $siteName
        ];

        $result = $this->pdo->query($sql, $params, true);

        return SiteFactory::create($result);
    }

    public function getSiteDbConfig(string $siteName): array
    {
        $sql = "SELECT d.Data, d.Info 
        FROM DBConfig d 
            INNER JOIN Centros c ON d.IdCentro = c.ID 
        WHERE c.Nombre = :siteName
	        AND c.Activo = :active
        ORDER BY d.Data";

        $params = [
            ':active' => self::ACTIVE_SITE_STATUS,
            ':siteName' => $siteName
        ];

        $result = $this->pdo->query($sql, $params);

        return !empty($result) ? $result : [];
    }
}
