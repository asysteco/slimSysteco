<?php

namespace App\Application\Actions\Login;

use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use App\Application\UseCase\Login\LogoutUseCase;

class LogoutAction
{
    private const SITE_SESSION = 'session-site';

    private LogoutUseCase $logoutUseCase;

    public function __construct(LogoutUseCase $logoutUseCase)
    {
        $this->logoutUseCase = $logoutUseCase;
    }

    public function __invoke(Request $request, Response $response): ResponseInterface
    {
        $site = $_SESSION[self::SITE_SESSION] ?? null;

        $response = $this->logoutUseCase->execute($request, $response, $site);

        return $response;
    }
}
