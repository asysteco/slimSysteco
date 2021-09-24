<?php

declare(strict_types=1);

namespace App\Application\Middleware;

use Exception;
use Slim\Routing\RouteContext;
use Dflydev\FigCookies\SetCookie;
use Psr\Http\Message\ResponseInterface;
use Dflydev\FigCookies\FigRequestCookies;
use Dflydev\FigCookies\FigResponseCookies;
use App\Application\Actions\Error\Error404Action;
use Psr\Http\Server\MiddlewareInterface as Middleware;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Infrastructure\Site\SiteReaderRepositoryInterface;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

class ValidateSiteMiddleware implements Middleware
{
    private const SESSION_VALUE = 'session-site';
    private const COOKIE_VALUE = 'site';

    private Error404Action $error404Action;
    private SiteReaderRepositoryInterface $siteReaderRepository;

    public function __construct(
        Error404Action $error404Action,
        SiteReaderRepositoryInterface $siteReaderRepository
    ) {
        $this->error404Action = $error404Action;
        $this->siteReaderRepository = $siteReaderRepository;
    }

    public function process(Request $request, RequestHandler $handler): ResponseInterface
    {
        try {
            $activeSites = $this->siteReaderRepository->getActiveSites();
        } catch (Exception $e) {
            return $this->error404Action->__invoke();
        }
        session_start();

        $siteCookie = FigRequestCookies::get($request, self::COOKIE_VALUE);
        if (
            $siteCookie &&
            in_array($siteCookie->getValue(), $activeSites, true) &&
            (isset($_SESSION[self::SESSION_VALUE]) && $_SESSION[self::SESSION_VALUE] === $siteCookie->getValue())
        ) {
            return $handler->handle($request);
        }

        $this->clearSessionSite();

        $route = RouteContext::fromRequest($request)->getRoute();
        $site = $route->getArgument(self::COOKIE_VALUE);

        if (in_array($site, $activeSites, true)) {
            $_SESSION[self::SESSION_VALUE] = $site;

            return FigResponseCookies::set(
                $handler->handle($request),
                SetCookie::create(self::COOKIE_VALUE)->withValue($site)->withDomain(APP_HOST)
            );
        }

        return $this->error404Action->__invoke();
    }


    private function clearSessionSite(): void
    {
        foreach ($_SESSION as $sessionVar) {
            unset($sessionVar);
        }
    }
}
