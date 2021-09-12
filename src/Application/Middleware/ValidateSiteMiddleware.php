<?php
declare(strict_types=1);

namespace App\Application\Middleware;

use Slim\Routing\RouteContext;
use Dflydev\FigCookies\SetCookie;
use Psr\Http\Message\ResponseInterface;
use Dflydev\FigCookies\FigRequestCookies;
use Dflydev\FigCookies\FigResponseCookies;
use App\Application\Actions\Error\Error404Action;
use Psr\Http\Server\MiddlewareInterface as Middleware;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

class ValidateSiteMiddleware implements Middleware
{
    private const ALLOWED_SITES = [
        'bezmiliana',
        TESTING
    ];

    private Error404Action $error404Action;

    public function __construct(Error404Action $error404Action)
    {
        $this->error404Action = $error404Action;
    }

    public function process(Request $request, RequestHandler $handler): ResponseInterface
    {
        $siteCookie = FigRequestCookies::get($request, 'site');
        if ($siteCookie && in_array($siteCookie->getValue(), self::ALLOWED_SITES, true)) {
            return $handler->handle($request);
        }
        
        $route = RouteContext::fromRequest($request)->getRoute();
        $site = $route->getArgument('site');

        if (in_array($site, self::ALLOWED_SITES, true)) {
            return FigResponseCookies::set(
                $handler->handle($request),
                SetCookie::create('site')->withValue($site)->withDomain(APP_HOST)
            );
        }

        return $this->error404Action->execute();
    }
}
 