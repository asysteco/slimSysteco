<?php

namespace App\Application\UseCase\Login;

use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Dflydev\FigCookies\FigResponseCookies;

class LogoutUseCase
{
    private const SITE_LOCATION = '/';

    public function execute(Request $request, Response $response, string $redirectSite): ResponseInterface
    {
        $redirectSite = !empty($redirectSite) ? $redirectSite : '';
        $response = $this->clearCookies($response);

        return $response->withHeader('Location', self::SITE_LOCATION . $redirectSite);
    }

    private function clearCookies(Response $response): Response
    {
        $response = FigResponseCookies::expire($response, JWT_TOKEN_NAME);

        return $response;
    }
}
