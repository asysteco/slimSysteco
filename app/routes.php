<?php
declare(strict_types=1);

use Slim\App;
use App\Application\Actions\Login\LoginAction;
use App\Application\Actions\User\ViewUserAction;
use App\Application\Actions\User\ListUsersAction;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Application\Middleware\ValidateSiteMiddleware;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;

return function (App $app) {
    $app->options('/{routes:.*}', function (Request $request, Response $response) {
        // CORS Pre-Flight OPTIONS Request Handler
        return $response;
    });

    $app->get('/login', LoginAction::class)->setName('login');

    $app->group('', function (Group $group) {
        $group->group('/users', function (Group $group) {
            $group->get('', ListUsersAction::class);
            $group->get('/{id}', ViewUserAction::class);
        });

        $group->get('/{site}', function (Request $request, Response $response) {
            return $response;
        });
    });
};
