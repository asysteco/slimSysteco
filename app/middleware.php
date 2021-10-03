<?php
declare(strict_types=1);

use Slim\App;
use Slim\Views\Twig;
use Slim\Views\TwigMiddleware;
use App\Application\Middleware\ValidateSiteMiddleware;

return function (App $app) {
    $app->add(ValidateSiteMiddleware::class)
    ->addBodyParsingMiddleware();

    $app->add(TwigMiddleware::createFromContainer($app, Twig::class));
};
