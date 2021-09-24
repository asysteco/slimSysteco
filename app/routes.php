<?php
declare(strict_types=1);

use Slim\App;
use App\Application\Actions\Login\LoginAction;
use App\Application\Actions\Login\LoginTwigAction;
use Psr\Http\Message\ResponseInterface as Response;
use App\Application\Middleware\LoginRedirectMiddleware;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;
use App\Application\Actions\Profesores\ProfesoresListTwigAction;

return function (App $app) {
    $app->group('', function (Group $group) {
        $group->get('/profesores', ProfesoresListTwigAction::class);
        $group->get('/login', LoginTwigAction::class)->setName('login');
        $group->get('/{site}', function (Request $request, Response $response) {
            return $response;
        })->add(LoginRedirectMiddleware::class);
    })->add(LoginRedirectMiddleware::class);

    $app->group('/xhr', function(Group $group) {
        $group->post('/login', LoginAction::class);
    });
};
