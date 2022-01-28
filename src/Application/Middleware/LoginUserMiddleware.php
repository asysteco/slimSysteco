<?php
declare(strict_types=1);

namespace App\Application\Middleware;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Psr\Http\Message\RequestInterface;
use Dflydev\FigCookies\FigRequestCookies;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Server\MiddlewareInterface as Middleware;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

class LoginUserMiddleware implements Middleware
{
    public function process(Request $request, RequestHandler $handler): Response
    {
        $request = $this->getTokenFromRequest($request);

        return $handler->handle($request);
    }

    private function getTokenFromRequest(Request $request): RequestInterface
    {
        $cookieToken = FigRequestCookies::get($request, JWT_TOKEN_NAME);

        if (empty($cookieToken->getValue())) {
            return $request;
        }

        $token = base64_decode($cookieToken->getValue());

        $key = new Key(JWT_CODE, JWT_METHOD);
        $decodedToken = JWT::decode($token, $key);

        if (isset($decodedToken->userId)) {
            $request = $request->withAttribute('userId', $decodedToken->userId);
        }

        if (isset($decodedToken->userId)) {
            $request = $request->withAttribute('userType', $decodedToken->userType);
        }

        if (isset($decodedToken->site)) {
            $request = $request->withAttribute('site', $decodedToken->site);
        }

        return $request;
    }
}