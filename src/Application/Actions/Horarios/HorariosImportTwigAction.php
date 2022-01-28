<?php

namespace App\Application\Actions\Horarios;

use Slim\Views\Twig;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use App\Application\UseCase\Horario\GetImportFormDataUseCase;

class HorariosImportTwigAction
{
    private Twig $twig;
    private GetImportFormDataUseCase $getImportFormDataUseCase;

    public function __construct(
        Twig $twig,
        GetImportFormDataUseCase $getImportFormDataUseCase
    ) {
        $this->twig = $twig;
        $this->getImportFormDataUseCase = $getImportFormDataUseCase;
    }

    public function __invoke(Request $request, Response $response): Response
    {
        $userType = $request->getAttribute('userType');
        $site = $request->getAttribute('site');
        $data = $this->getImportFormDataUseCase->execute($site);

        return $this->twig->render(
            $response,
            'horarios/importFormView.twig',
            [
                'title' => 'Importar Horarios',
                'menu' => 'horarios',
                'section' => 'importHorarios',
                'userType' => $userType,
                'siteInfo' => $data['siteInfo'],
                'franjas' => $data['franjas'],
                'horariosExists' => $data['horariosExists']
            ]
        );
    }
}
