<?php

declare(strict_types=1);

use Twig\Environment;
use DI\ContainerBuilder;
use Twig\Loader\FilesystemLoader;
use Psr\Container\ContainerInterface;

/** @var ContainerBuilder $containerBuilder */
$containerBuilder->addDefinitions([
    FilesystemLoader::class => static function () {
        return new FilesystemLoader(BASE_PATH . '/templates');
    },
    Environment::class => static function (ContainerInterface $c) {
        return new Environment(
            $c->get(FilesystemLoader::class),
            $c->get('TwigSettings')->get('settings')
        );
    }
]);
