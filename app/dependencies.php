<?php
declare(strict_types=1);

use App\Application\Actions\Error\Error404Action;
use App\Application\Middleware\ValidateSiteMiddleware;
use App\Application\Settings\SettingsInterface;
use DI\ContainerBuilder;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\UidProcessor;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        LoggerInterface::class => function (ContainerInterface $c) {
            $settings = $c->get(SettingsInterface::class);

            $loggerSettings = $settings->get('logger');
            $logger = new Logger($loggerSettings['name']);

            $processor = new UidProcessor();
            $logger->pushProcessor($processor);

            $handler = new StreamHandler($loggerSettings['path'], $loggerSettings['level']);
            $logger->pushHandler($handler);

            return $logger;
        },
        ValidateSiteMiddleware::class => static function (ContainerInterface $c) {
            return new ValidateSiteMiddleware(
                $c->get(Error404Action::class),
                $c->get('GestReaderConnection')
            ); 
        }
    ]);
    require __DIR__ . '/dependencies/DataBaseDependencies.php';
    require __DIR__ . '/dependencies/TwigDependencies.php';
};
