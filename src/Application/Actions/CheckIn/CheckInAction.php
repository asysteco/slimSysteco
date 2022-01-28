<?php

namespace App\Application\Actions\CheckIn;

use App\Application\Actions\ActionResponseTrait;
use App\Application\UseCase\Fichar\CheckInUseCase;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Slim\Views\Twig;

class CheckInAction
{
    use ActionResponseTrait;

    private Twig $twig;
    private CheckInUseCase $chekInUseCase;

    public function __construct(
        Twig $twig,
        CheckInUseCase $chekInUseCase
    ) {
        $this->twig = $twig;
        $this->chekInUseCase = $chekInUseCase;
    }

    public function __invoke(Request $request, Response $response): Response
    {
        try {
            $params = $request->getParsedBody();
            $qrCode = $params['qrCode'] ?? null;
            $siteOptions = $request->getAttribute('site-options');
            $result = $this->chekInUseCase->execute($qrCode, $siteOptions);
            
            $responseData = $this->successResponse($result);
        } catch (\Exception $e) {
            $responseData = $this->errorResponse([$e->getMessage()]);
        }

        $response->getBody()->write($responseData);
        return $response;
    }
}
