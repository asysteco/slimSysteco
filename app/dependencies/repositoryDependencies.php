<?php

declare(strict_types=1);

use DI\ContainerBuilder;
use Psr\Container\ContainerInterface;
use App\Infrastructure\Site\SiteReaderRepository;
use App\Infrastructure\User\UserReaderRepository;
use App\Infrastructure\Aulas\AulasReaderRepository;
use App\Infrastructure\Profesor\ProfesorReaderRepository;
use App\Infrastructure\Site\SiteReaderRepositoryInterface;
use App\Infrastructure\User\UserReaderRepositoryInterface;
use App\Infrastructure\Aulas\AulasReaderRepositoryInterface;
use App\Infrastructure\Profesor\ProfesorReaderRepositoryInterface;

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
    },
    ProfesorReaderRepositoryInterface::class => static function (ContainerInterface $c) {
        return new ProfesorReaderRepository(
            $c->get('SiteRWConnection')
        );
    },
    AulasReaderRepositoryInterface::class => static function (ContainerInterface $c) {
        return new AulasReaderRepository(
            $c->get('SiteRWConnection')
        );
    }
]);
