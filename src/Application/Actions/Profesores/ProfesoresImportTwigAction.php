<?php

namespace App\Application\Actions\Profesores;

use Slim\Views\Twig;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use App\Application\UseCase\Horario\GetImportFormDataUseCase;

class ProfesoresImportTwigAction
{
    private Twig $twig;

    public function __construct(Twig $twig)
    {
        $this->twig = $twig;
    }

    public function __invoke(Request $request, Response $response): Response
    {
        $userType = $request->getAttribute('userType');

        return $this->twig->render(
            $response,
            'profesores/importFormView.twig',
            [
                'title' => 'Importar Personal',
                'menu' => 'profesores',
                'section' => 'importProfesores',
                'userType' => $userType
            ]
        );
    }
}
