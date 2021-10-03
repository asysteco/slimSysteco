<?php

declare(strict_types=1);

use DI\ContainerBuilder;
use Psr\Container\ContainerInterface;
use App\Infrastructure\Aula\AulaReaderRepository;
use App\Infrastructure\Site\SiteReaderRepository;
use App\Infrastructure\User\UserReaderRepository;
use App\Infrastructure\Curso\CursoReaderRepository;
use App\Infrastructure\Profesor\ProfesorReaderRepository;
use App\Infrastructure\Aula\AulaReaderRepositoryInterface;
use App\Infrastructure\Site\SiteReaderRepositoryInterface;
use App\Infrastructure\User\UserReaderRepositoryInterface;
use App\Infrastructure\Profesor\ProfesorReaderRepositoryInterface;
use App\Infrastructure\Curso\CursoReaderRepositoryInterface;

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
    AulaReaderRepositoryInterface::class => static function (ContainerInterface $c) {
        return new AulaReaderRepository(
            $c->get('SiteRWConnection')
        );
    },
    CursoReaderRepositoryInterface::class => static function (ContainerInterface $c) {
        return new CursoReaderRepository(
            $c->get('SiteRWConnection')
        );
    }
]);
