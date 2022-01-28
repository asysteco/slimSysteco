<?php

namespace App\Application\Actions\Aulas;

use App\Application\UseCase\Aula\GetAulasUseCase;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Slim\Views\Twig;

class AulasListTwigAction
{
    private Twig $twig;
    private GetAulasUseCase $getAulasUseCase;

    public function __construct(
        Twig $twig,
        GetAulasUseCase $getAulasUseCase
    ) {
        $this->twig = $twig;
        $this->getAulasUseCase = $getAulasUseCase;
    }

    public function __invoke(Request $request, Response $response): Response
    {
        $userType = $request->getAttribute('userType');
        $aulas = $this->getAulasUseCase->execute();

        return $this->twig->render(
            $response,
            'aulas/aulasView.twig',
            [
                'title' => 'Gestionar Aulas',
                'menu' => 'horarios',
                'section' => 'aulas',
                'userType' => $userType,
                'aulas' => $aulas
            ]
        );
    }
}
