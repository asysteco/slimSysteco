<?php

declare(strict_types=1);

namespace App\Application\Middleware;

use Exception;
use Firebase\JWT\JWT;
use Slim\Routing\RouteContext;
use App\Domain\Sites\SiteOptions;
use Dflydev\FigCookies\SetCookie;
use Psr\Http\Message\ResponseInterface;
use Dflydev\FigCookies\FigRequestCookies;
use Dflydev\FigCookies\FigResponseCookies;
use App\Application\UseCase\Login\LogoutUseCase;
use App\Application\Actions\Error\Error404Action;
use Psr\Http\Server\MiddlewareInterface as Middleware;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Infrastructure\Site\SiteReaderRepositoryInterface;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

class ValidateSiteMiddleware implements Middleware
{
    private const LOGIN_URL = '/login';
    private const SITE_OPTIONS = 'site-options';
    private const SITE_DB = 'site-db';
    private const SITE_VALUE = 'site';
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

        $route = RouteContext::fromRequest($request)->getRoute();
        $requestSite = $route->getArgument(self::SITE_VALUE);
        $cookieSite = $request->getAttribute(self::SITE_VALUE);

        if (
            $cookieSite &&
            in_array($cookieSite, $activeSites, true)
        ) {
            if ($requestSite !== null && $cookieSite !== $requestSite) {
                $paramsToEncode = [
                    'site' => $requestSite
                ];

                $this->clearSession();
                $response = $this->toCookie($paramsToEncode, $handler->handle($request))->withAddedHeader('Location', self::LOGIN_URL);
                return $response->withStatus(302);
            }
            $request = $this->setSiteOptions($request, $cookieSite);
            return $handler->handle($request);
        }

        if (in_array($requestSite, $activeSites, true)) {
            $paramsToEncode = [
                'site' => $requestSite
            ];

            return $this->toCookie($paramsToEncode, $handler->handle($request));
        }

        return $this->error404Action->__invoke();
    }

    private function setSiteOptions(Request $request, string $cookieSite): Request
    {
        $siteOptions = $this->siteReaderRepository->getSiteInfoByName($cookieSite)->options();

        if (!isset($_SESSION[self::SITE_DB]) || empty($_SESSION[self::SITE_DB])) {
            $siteDbConfig = $this->siteReaderRepository->getSiteDbConfig($cookieSite);
            $_SESSION[self::SITE_DB] = $siteDbConfig;
        }

        $request = $request->withAttribute(self::SITE_OPTIONS, $siteOptions);

        return $request;
    }

    private function toCookie(array $params, Response $response): Response
    {
        $jwtValue = base64_encode(JWT::encode($params, JWT_CODE, JWT_METHOD));

        return FigResponseCookies::set(
            $response,
            SetCookie::create(JWT_TOKEN_NAME, $jwtValue)
            ->withPath(self::ROOT_PATH)
            ->withDomain(APP_HOST)
        );
    }

    private function clearSession(): void
    {
        if (isset($_SESSION)) {
            session_unset();
        }
    }
}
