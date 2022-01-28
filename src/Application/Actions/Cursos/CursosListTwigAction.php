<?php

namespace App\Application\Actions\Cursos;

use App\Application\UseCase\Curso\GetCursosUseCase;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Slim\Views\Twig;

class CursosListTwigAction
{
    private Twig $twig;
    private GetCursosUseCase $getCursosUseCase;

    public function __construct(
        Twig $twig,
        GetCursosUseCase $getCursosUseCase
    ) {
        $this->twig = $twig;
        $this->getCursosUseCase = $getCursosUseCase;
    }

    public function __invoke(Request $request, Response $response): Response
    {
        $userType = $request->getAttribute('userType');
        $cursos = $this->getCursosUseCase->execute();

        return $this->twig->render(
            $response,
            'cursos/cursosView.twig',
            [
                'title' => 'Gestionar Cursos',
                'menu' => 'horarios',
                'section' => 'cursos',
                'userType' => $userType,
                'cursos' => $cursos
            ]
        );
    }
}
