<?php
declare(strict_types=1);

use App\Application\Middleware\LoginRedirectMiddleware;
use App\Application\Middleware\ValidateSiteMiddleware;
use Slim\App;

return function (App $app) {
    $app->add(LoginRedirectMiddleware::class)->add(ValidateSiteMiddleware::class);
};
