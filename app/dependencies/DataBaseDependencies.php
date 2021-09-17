<?php

use App\Infrastructure\PDO\PdoDataAccess;
use DI\ContainerBuilder;
use Psr\Container\ContainerInterface;

/** @var ContainerBuilder $containerBuilder */
$containerBuilder->addDefinitions([
    'GestReaderConnection' => static function(ContainerInterface $c) {
        $settings = $c->get('DataBaseSettings')->get('GestReader');
        $dsn = $settings['engine'] . 
            ':host=' . $settings['host'] .
            ';dbname=' . $settings['dbname'] .
            ';port=' . $settings['port'] .
            ';charset=' . $settings['charset'];

            return new PdoDataAccess($dsn, $settings['user'], $settings['password']);
    }
]);