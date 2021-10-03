<?php
declare(strict_types=1);

namespace App\Application\Middleware;

use Slim\Routing\RouteContext;
use Dflydev\FigCookies\FigRequestCookies;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Server\MiddlewareInterface as Middleware;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

class LoginRedirectMiddleware implements Middleware
{
    private const SESSION_ID = 'session-id';
    private const COOKIE_SESSION = 'session';

    public function process(Request $request, RequestHandler $handler): Response
    {
        $requestUri = $request->getUri()->getPath();
        $routeParser = RouteContext::fromRequest($request)->getRouteParser();
        $loginUri = $routeParser->urlFor('login');
        $sessionCookie = FigRequestCookies::get($request, self::COOKIE_SESSION);

        if (
            $requestUri === $loginUri ||
            (isset($_SESSION[self::SESSION_ID]) && $_SESSION[self::SESSION_ID] === $sessionCookie->getValue() && $requestUri !== $loginUri)
        ) {
            return $handler->handle($request);
        }
        

        return $handler->handle($request)->withHeader('Location', $loginUri)->withStatus(302);
    }
}
 