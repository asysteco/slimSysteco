<?php

namespace App\Application\Actions\Login;

use Exception;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use App\Domain\User\User;
use Dflydev\FigCookies\SetCookie;
use Psr\Http\Message\ResponseInterface;
use Dflydev\FigCookies\FigResponseCookies;
use App\Application\UseCase\Login\LoginUseCase;
use App\Application\Actions\ActionResponseTrait;

class LoginAction
{
    use ActionResponseTrait;

    private const SESSION_ID = 'session-id';
    private const USER_ID = 'user-id';
    private const COOKIE_SESSION = 'session';
    private const ROOT_PATH = '/';

    private LoginUseCase $loginUseCase;

    public function __construct(
        LoginUseCase $loginUseCase
    ) {
        $this->loginUseCase = $loginUseCase;
    }

    public function __invoke(Request $request, Response $response): ResponseInterface
    {
        try {
            $params = $request->getParsedBody();
            $username = $params['username'] ?? null;
            $password = $params['password'] ?? null;
            $user = $this->loginUseCase->execute($username, $password);
            $responseData = $this->successResponse();
            
            $sessionCookie = $this->setSessionValues($user);
            
        } catch (Exception $e) {
            $responseData = $this->errorResponse();
        }


        if (isset($sessionCookie)) {
            $response = FigResponseCookies::set(
                $response,
                SetCookie::create(self::COOKIE_SESSION, $sessionCookie)
                    ->withPath(self::ROOT_PATH)
                    ->withDomain(APP_HOST)
            );
        }
        $response->getBody()->write($responseData);

        return $response->withHeader('Content-Type', 'application/json');
    }

    private function setSessionValues(User $user): string
    {
        $hash = sha1($user->id() . date('Ymd'));
        $_SESSION[self::SESSION_ID] = $hash;
        $_SESSION[self::USER_ID] = $user->id();

        return $hash;
    }
}
