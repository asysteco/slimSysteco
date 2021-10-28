<?php

namespace App\Application\Actions\Asistencias;

use App\Application\Actions\ActionResponseTrait;
use App\Application\UseCase\Asistencias\GetAsistenciasUseCase;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Slim\Views\Twig;

class AsistenciasListAction
{
    use ActionResponseTrait;

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
        try {
            $params = $request->getParsedBody();
            $date = $params['date'] ?? null;
            $result = $this->getAsistenciasUseCase->execute($date);
            
            $responseData = $this->successResponse($result);
        } catch (\Exception $e) {
            $responseData = $this->errorResponse([$e->getMessage()]);
        }

        $response->getBody()->write($responseData);
        return $response;
    }
}
