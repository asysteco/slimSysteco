<?php

namespace App\Application\UseCase\Login;

use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Dflydev\FigCookies\FigResponseCookies;

class LogoutUseCase
{
    private const SITE_LOCATION = '/';
    private const SITE_COOKIE = 'site';
    private const SESSION_COOKIE = 'session';
    private const SESSION_ID = 'PHPSESSID';

    public function execute(Request $request, Response $response, string $redirectSite): ResponseInterface
    {
        $redirectSite = !empty($redirectSite) ? $redirectSite : '';
        $this->clearSession();
        $response = $this->clearCookies($response);

        return $response->withAddedHeader('Location', self::SITE_LOCATION . $redirectSite);
    }

    private function clearSession()
    {
        if (isset($_SESSION)) {
            session_unset();
        }
    }

    private function clearCookies($response)
    {
        $response = FigResponseCookies::expire($response, self::SITE_COOKIE);
        $response = FigResponseCookies::expire($response, self::SESSION_COOKIE);
        $response = FigResponseCookies::expire($response, self::SESSION_ID);

        return $response;
    }
}
