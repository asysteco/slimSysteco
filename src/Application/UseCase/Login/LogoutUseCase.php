<?php

namespace App\Application\UseCase\Login;

use Dflydev\FigCookies\FigRequestCookies;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Dflydev\FigCookies\FigResponseCookies;

class LogoutUseCase
{
    private const SITE_LOCATION = '/';

    public function execute(Request $request, Response $response, ?string $redirectSite = null): ResponseInterface
    {
        $redirectSite = !empty($redirectSite) ? $redirectSite : $this->getSiteFromRequest($request);
        $response = $this->clearCookies($response);
        $this->clearSession();

        return $response->withHeader('Location', self::SITE_LOCATION . $redirectSite);
    }

    private function getSiteFromRequest(Request $request): string
    {
        return $request->getAttribute('site');
    }

    private function clearCookies(Response $response): Response
    {
        $response = FigResponseCookies::expire($response, JWT_TOKEN_NAME);

        return $response;
    }

    private function clearSession(): void
    {
        if (isset($_SESSION)) {
            session_unset();
        }
    }
}
