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
use App\Application\UseCase\Login\LogoutUseCase;
use App\Domain\Sites\SiteOptions;
use Psr\Http\Server\MiddlewareInterface as Middleware;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Infrastructure\Site\SiteReaderRepositoryInterface;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

class ValidateSiteMiddleware implements Middleware
{
    private const SESSION_VALUE = 'session-site';
    private const SITE_OPTIONS = 'site-options';
    private const SITE_DB = 'site-db';
    private const COOKIE_VALUE = 'site';
    private const ROOT_PATH = '/';

    private Error404Action $error404Action;
    private SiteReaderRepositoryInterface $siteReaderRepository;
    private LogoutUseCase $logoutUseCase;

    public function __construct(
        Error404Action $error404Action,
        SiteReaderRepositoryInterface $siteReaderRepository,
        LogoutUseCase $logoutUseCase
    ) {
        $this->error404Action = $error404Action;
        $this->siteReaderRepository = $siteReaderRepository;
        $this->logoutUseCase = $logoutUseCase;
    }

    public function process(Request $request, RequestHandler $handler): ResponseInterface
    {
        try {
            $activeSites = $this->siteReaderRepository->getActiveSites();
        } catch (Exception $e) {
            return $this->error404Action->__invoke();
        }
        session_start();

        $route = RouteContext::fromRequest($request)->getRoute();
        $site = $route->getArgument(self::COOKIE_VALUE);

        $siteCookie = FigRequestCookies::get($request, self::COOKIE_VALUE);
        if (
            $siteCookie &&
            in_array($siteCookie->getValue(), $activeSites, true) &&
            (isset($_SESSION[self::SESSION_VALUE]) && $_SESSION[self::SESSION_VALUE] === $siteCookie->getValue())
        ) {
            if ($site !== null && $siteCookie->getValue() !== $site) {
                $_SESSION[self::SESSION_VALUE] = $site;
                $response = $this->logoutUseCase->execute($request, $handler->handle($request), $site);

                return $response->withStatus(302);
            }
            $request = $this->setSiteOptions($request);
            return $handler->handle($request);
        }

        $this->clearSessionSite();

        if (in_array($site, $activeSites, true)) {
            $_SESSION[self::SESSION_VALUE] = $site;

            return FigResponseCookies::set(
                $handler->handle($request),
                SetCookie::create(self::COOKIE_VALUE, $site)
                ->withPath(self::ROOT_PATH)
                ->withDomain(APP_HOST)
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

    private function setSiteOptions(Request $request): Request
    {
        if (!isset($_SESSION[self::SITE_OPTIONS]) || !$_SESSION[self::SITE_OPTIONS] instanceof SiteOptions) {
            $siteOptions = $this->siteReaderRepository->getSiteInfoByName($_SESSION[self::SESSION_VALUE])->options();
        } else {
            $siteOptions = $_SESSION[self::SITE_OPTIONS];
        }

        if (!isset($_SESSION[self::SITE_DB]) || empty($_SESSION[self::SITE_DB])) {
            $siteDbConfig = $this->siteReaderRepository->getSiteDbConfig($_SESSION[self::SESSION_VALUE]);
            $_SESSION[self::SITE_DB] = $siteDbConfig;
        }
        
        $request = $request->withAttribute(self::SESSION_VALUE, $_SESSION[self::SESSION_VALUE]);
        $request = $request->withAttribute(self::SITE_OPTIONS, $siteOptions);

        return $request;
    }
}
