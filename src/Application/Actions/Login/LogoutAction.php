<?php

namespace App\Application\Actions\Login;

use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use App\Application\UseCase\Login\LogoutUseCase;

class LogoutAction
{
    private LogoutUseCase $logoutUseCase;

    public function __construct(LogoutUseCase $logoutUseCase)
    {
        $this->logoutUseCase = $logoutUseCase;
    }

    public function __invoke(Request $request, Response $response): ResponseInterface
    {
        $response = $this->logoutUseCase->execute($request, $response);

        return $response;
    }
}
