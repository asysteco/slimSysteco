<?php
namespace App\Domain\Sites;

use App\Domain\Sites\Site;
use App\Domain\Sites\SiteOptions;

class SiteFactory
{
    public static function create(array $site): Site {
        $cryptKey = $site['CriptKey'] ?? null;
        $dailyQr = $site['QrDiario'] ?? null;
        $googleQr = $site['GoogleQR'] ?? null;
        $ficharSalida = $site['FicharSalida'] ?? null;
        $qrReader = $site['LectorQr'] ?? null;
        $edificios = $site['Edificios'] ?? null;
        $autoScroll = $site['AutoScroll'] ?? null;

        $options = new SiteOptions(
            $cryptKey,
            $dailyQr,
            $googleQr,
            $ficharSalida,
            $qrReader,
            $edificios,
            $autoScroll
        );

        return new Site(
            $site['ID'],
            $site['Nombre'],
            $options
        );
    }

    public static function createFromResultSet(array $resultSet): array
    {
        return array_map(
            function ($site) {
                return self::create($site);
            },
            $resultSet
        );
    }
}