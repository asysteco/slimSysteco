<?php

declare(strict_types=1);

use App\Infrastructure\PDO\PdoDataAccess;
use DI\ContainerBuilder;
use Psr\Container\ContainerInterface;

/** @var ContainerBuilder $containerBuilder */
$containerBuilder->addDefinitions([
    'GestReaderConnection' => static function (ContainerInterface $c) {
        $settings = $c->get('DataBaseSettings')->get('GestReader');
        $dsn = $settings['engine'] .
            ':host=' . $settings['host'] .
            ';dbname=' . $settings['dbname'] .
            ';port=' . $settings['port'] .
            ';charset=' . $settings['charset'];

        return new PdoDataAccess($dsn, $settings['user'], $settings['password']);
    },
    'SiteRWConnection' => static function (ContainerInterface $c) {
        $settings = $c->get('DataBaseSettings')->get('SiteReaderWriter');
        $dsn = $settings['engine'] .
            ':host=' . $settings['host'] .
            ';dbname=' . $settings['dbname'] .
            ';port=' . $settings['port'] .
            ';charset=' . $settings['charset'];

        return new PdoDataAccess($dsn, $settings['user'], $settings['password']);
    }
]);
