<?php
declare(strict_types=1);

namespace App\Application\Middleware;

use Slim\Routing\RouteContext;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Server\MiddlewareInterface as Middleware;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

class LoginRedirectMiddleware implements Middleware
{
    public function process(Request $request, RequestHandler $handler): Response
    {
        $requestUri = $request->getUri()->getPath();
        $routeParser = RouteContext::fromRequest($request)->getRouteParser();
        $loginUri = $routeParser->urlFor('login');
        $userId = $request->getAttribute('userId');

        if ( $requestUri === $loginUri || !empty($userId)) {
            return $handler->handle($request);
        }
        

        return $handler->handle($request)->withHeader('Location', $loginUri)->withStatus(302);
    }
}
 