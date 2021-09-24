<?php

declare(strict_types=1);

use DI\ContainerBuilder;
use Psr\Container\ContainerInterface;
use App\Application\Actions\Error\Error404Action;
use App\Application\Middleware\ValidateSiteMiddleware;
use App\Infrastructure\Site\SiteReaderRepositoryInterface;

/** @var ContainerBuilder $containerBuilder */
$containerBuilder->addDefinitions([
    ValidateSiteMiddleware::class => static function (ContainerInterface $c) {
        return new ValidateSiteMiddleware(
            $c->get(Error404Action::class),
            $c->get(SiteReaderRepositoryInterface::class)
        );
    }
]);
