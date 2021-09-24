<?php
declare(strict_types=1);

use Slim\App;
use App\Application\Middleware\ValidateSiteMiddleware;

return function (App $app) {
    $app->add(ValidateSiteMiddleware::class)
    ->addBodyParsingMiddleware();
};
