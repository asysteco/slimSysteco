<?php

declare(strict_types=1);

use Slim\Views\Twig;
use DI\ContainerBuilder;
use Twig\Loader\FilesystemLoader;
use Odan\Twig\TwigAssetsExtension;
use Twig\Extension\DebugExtension;
use Psr\Container\ContainerInterface;

/** @var ContainerBuilder $containerBuilder */
$containerBuilder->addDefinitions([
    FilesystemLoader::class => static function () {
        return new FilesystemLoader([
            BASE_PATH . '/templates',
            BASE_PATH . '/public/media'
        ]);
    },
    Twig::class => static function (ContainerInterface $c) {
        $settings = $c->get('TwigSettings');
        $twigSettings = $settings->get('settings');
        $twig = Twig::create($c->get(FilesystemLoader::class)->getPaths(), $twigSettings);
        $environment = $twig->getEnvironment();

        // Add Twig extensions
        $twig->addExtension(new DebugExtension());
        $twig->addExtension(new TwigAssetsExtension($environment, $settings->get('assetOptions')));

        return $twig;
    }
]);
