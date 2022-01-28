<?php
declare(strict_types=1);

use App\Application\Middleware\LoginUserMiddleware;
use Slim\App;
use Slim\Views\Twig;
use Slim\Views\TwigMiddleware;
use App\Application\Middleware\ValidateSiteMiddleware;

return function (App $app) {
    $app->add(ValidateSiteMiddleware::class)
    ->add(LoginUserMiddleware::class)
    ->addBodyParsingMiddleware();

    $app->add(TwigMiddleware::createFromContainer($app, Twig::class));
};
