<?php

namespace App\Application\Actions\Guardias;

use Slim\Views\Twig;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use App\Application\UseCase\Guardias\GetGuardiasUseCase;

class GuardiasListTwigAction
{
    private Twig $twig;
    private GetGuardiasUseCase $getGuardiasUseCase;

    public function __construct(
        Twig $twig,
        GetGuardiasUseCase $getGuardiasUseCase
    ) {
        $this->twig = $twig;
        $this->getGuardiasUseCase = $getGuardiasUseCase;
    }

    public function __invoke(Request $request, Response $response): Response
    {
        $userType = $request->getAttribute('userType');
        $siteOptions = $request->getAttribute('site-options');
        $guardias = $this->getGuardiasUseCase->execute();

        return $this->twig->render(
            $response,
            'guardias/guardiasView.twig',
            [
                'title' => 'Guardias',
                'menu' => 'guardias',
                'userType' => $userType,
                'guardias' => $guardias,
                'siteOptions' => $siteOptions
            ]
        );
    }
}
