<?php

namespace App\Application\Actions\Asistencias;

use App\Application\UseCase\Asistencias\GetAsistenciasUseCase;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Slim\Views\Twig;

class AsistenciasListTwigAction
{
    private Twig $twig;
    private GetAsistenciasUseCase $getAsistenciasUseCase;

    public function __construct(
        Twig $twig,
        GetAsistenciasUseCase $getAsistenciasUseCase
    ) {
        $this->twig = $twig;
        $this->getAsistenciasUseCase = $getAsistenciasUseCase;
    }

    public function __invoke(Request $request, Response $response): Response
    {
        $userType = $request->getAttribute('userType');
        $result = $this->getAsistenciasUseCase->execute();

        return $this->twig->render(
            $response,
            'asistencias/asistenciasView.twig',
            [
                'title' => 'Asistencias',
                'menu' => 'asistencias',
                'section' => 'asistencias',
                'userType' => $userType,
                'fichajes' => $result['fichajes'],
                'faltas' => $result['faltas']
            ]
        );
    }
}
