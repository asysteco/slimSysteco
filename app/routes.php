<?php
declare(strict_types=1);

use Slim\App;
use App\Application\Actions\Login\LoginAction;
use App\Application\Actions\Login\LogoutAction;
use App\Application\Actions\Login\QrLoginAction;
use App\Application\Actions\Login\LoginTwigAction;
use App\Application\Middleware\LoginUserMiddleware;
use Psr\Http\Message\ResponseInterface as Response;
use App\Application\Actions\Login\QrLoginTwigAction;
use App\Application\Actions\Aulas\AulasListTwigAction;
use App\Application\Middleware\LoginRedirectMiddleware;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Application\Actions\Cursos\CursosListTwigAction;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;
use App\Application\Actions\Guardias\GuardiasListTwigAction;
use App\Application\Actions\Asistencias\AsistenciasListAction;
use App\Application\Actions\Horarios\HorariosImportTwigAction;
use App\Application\Actions\Profesores\ProfesoresAddTwigAction;
use App\Application\Actions\Profesores\ProfesoresListTwigAction;
use App\Application\Actions\Asistencias\AsistenciasListTwigAction;
use App\Application\Actions\Profesores\ProfesoresImportTwigAction;

return function (App $app) {
    $app->get('/logout', LogoutAction::class)->setName('logout');

    $app->group('', function (Group $group) {
        $group->get('/cursos', CursosListTwigAction::class)->setName('cursos-list')->add(LoginUserMiddleware::class);

        $group->get('/aulas', AulasListTwigAction::class)->setName('aulas-list')->add(LoginUserMiddleware::class);
            
        $group->group('/horarios', function (Group $group) {
            $group->get('/import', HorariosImportTwigAction::class)->setName('horarios-import');
        })->add(LoginUserMiddleware::class);

        $group->group('/profesores', function (Group $group) {
            $group->get('', ProfesoresListTwigAction::class)->setName('profesores-main');
            $group->get('/list', ProfesoresListTwigAction::class)->setName('profesores-list');
            $group->get('/add', ProfesoresAddTwigAction::class)->setName('profesores-add');
            $group->get('/import', ProfesoresImportTwigAction::class)->setName('profesores-import');
        })->add(LoginUserMiddleware::class);
        
        $group->get('/asistencias', AsistenciasListTwigAction::class)->setName('asistencias-list')->add(LoginUserMiddleware::class);

        $group->get('/login', LoginTwigAction::class)->setName('login');

        $group->get('/guardias', GuardiasListTwigAction::class)->setName('guardias-main');

        $group->get('/{site:[a-zA-Z-]+}', function (Request $request, Response $response) {
            return $response->withAddedHeader('Location', '/profesores');
        });
    })->add(LoginRedirectMiddleware::class);

    
    $app->get('/reader/{site:[a-zA-Z-]+}', QrLoginTwigAction::class);

    $app->group('/xhr', function(Group $group) {
        $group->post('/login', LoginAction::class);
        $group->post('/qrLogin', QrLoginAction::class);

        $group->post('/checkIn', CheckInAction::class)->add(LoginRedirectMiddleware::class);
        $group->post('/asistencias/filter', AsistenciasListAction::class)->add(LoginRedirectMiddleware::class);
    });
};
