<?php

declare(strict_types=1);

use DI\ContainerBuilder;
use Psr\Container\ContainerInterface;
use App\Infrastructure\Site\SiteReaderRepository;
use App\Infrastructure\User\UserReaderRepository;
use App\Infrastructure\Site\SiteReaderRepositoryInterface;
use App\Infrastructure\User\UserReaderRepositoryInterface;

/** @var ContainerBuilder $containerBuilder */
$containerBuilder->addDefinitions([
    SiteReaderRepositoryInterface::class => static function (ContainerInterface $c) {
        return new SiteReaderRepository(
            $c->get('GestReaderConnection')
        );
    },
    UserReaderRepositoryInterface::class => static function (ContainerInterface $c) {
        return new UserReaderRepository(
            $c->get('SiteRWConnection')
        );
    }
]);
