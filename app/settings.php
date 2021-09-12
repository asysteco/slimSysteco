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
