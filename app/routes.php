<?php
declare(strict_types=1);

use Slim\App;
use App\Application\Actions\Login\LoginAction;
use App\Application\Actions\Login\LoginTwigAction;
use App\Application\Middleware\LoginUserMiddleware;
use Psr\Http\Message\ResponseInterface as Response;
use App\Application\Actions\Aulas\AulasListTwigAction;
use App\Application\Middleware\LoginRedirectMiddleware;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Application\Actions\Cursos\CursosListTwigAction;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;
use App\Application\Actions\Profesores\ProfesoresAddTwigAction;
use App\Application\Actions\Profesores\ProfesoresListTwigAction;

return function (App $app) {
    $app->group('', function (Group $group) {
        $group->get('/cursos', CursosListTwigAction::class)->setName('cursos-list')
            ->add(LoginUserMiddleware::class);

        $group->get('/aulas', AulasListTwigAction::class)->setName('aulas-list')
            ->add(LoginUserMiddleware::class);

        $group->group('/profesores', function (Group $group) {
            $group->get('', ProfesoresListTwigAction::class)->setName('profesores-main');
            $group->get('/list', ProfesoresListTwigAction::class)->setName('profesores-list');
            $group->get('/add', ProfesoresAddTwigAction::class)->setName('profesores-add');
            
        })->add(LoginUserMiddleware::class);

        $group->get('/login', LoginTwigAction::class)->setName('login');
        $group->get('/{site:[a-zA-Z]+}', function (Request $request, Response $response) {
            return $response->withAddedHeader('Location', '/profesores');
        });
    })->add(LoginRedirectMiddleware::class);

    $app->group('/xhr', function(Group $group) {
        $group->post('/login', LoginAction::class);
    });
};
