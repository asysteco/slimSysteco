<?php
declare(strict_types=1);

use App\Application\Settings\Settings;
use App\Application\Settings\SettingsInterface;
use DI\ContainerBuilder;
use Monolog\Logger;

return function (ContainerBuilder $containerBuilder) {

    // Global Settings Object
    $containerBuilder->addDefinitions([
        SettingsInterface::class => function () {
            return new Settings([
                'displayErrorDetails' => ENVIRONMENT === DEVELOPMENT || ENVIRONMENT === TESTING ? true : false,
                'logError'            => false,
                'logErrorDetails'     => false,
                'logger' => [
                    'name' => 'asysteco-app',
                    'path' => isset($_ENV['docker']) ? 'php://stdout' : __DIR__ . '/../logs/asysteco-app.log',
                    'level' => Logger::DEBUG,
                ],
            ]);
        },
        'DataBaseSettings' => function() {
            return new Settings([
                'GestReader' => [
                    'engine' => $_ENV['DB_GEST_ENGINE'],
                    'host' => $_ENV['DB_GEST_HOSTNAME'],
                    'dbname' => $_ENV['DB_GEST_DBNAME'],
                    'user' => $_ENV['DB_GEST_USERNAME'],
                    'password' => $_ENV['DB_GEST_PASSWORD'],
                    'port' => $_ENV['DB_GEST_PORT'],
                    'charset' => $_ENV['DB_GEST_CHARSET']
                ],
                'SiteReaderWriter' => [
                    'engine' => $_ENV['DB_SITE_ENGINE'],
                    'host' => $_ENV['DB_SITE_HOSTNAME'],
                    'dbname' => $_ENV['DB_SITE_DBNAME'],
                    'user' => $_ENV['DB_SITE_USERNAME'],
                    'password' => $_ENV['DB_SITE_PASSWORD'],
                    'port' => $_ENV['DB_SITE_PORT'],
                    'charset' => $_ENV['DB_SITE_CHARSET']
                ],
            ]);
        },
        'TwigSettings' => function() {
            return new Settings([
                'settings' => [
                    'cache' => BASE_PATH . '/templates/cache',
                    'auto_reload' => ENVIRONMENT === DEVELOPMENT || ENVIRONMENT === TESTING ? true : false
                ],
            ]);
        }
    ]);
};
