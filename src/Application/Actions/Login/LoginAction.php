<?php

namespace App\Application\Actions\Login;

use Exception;
use Firebase\JWT\JWT;
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

            $response = $this->setCookies($user, $request, $response);            
        } catch (Exception $e) {
            $responseData = $this->errorResponse();
        }

        $response->getBody()->write($responseData);

        return $response->withHeader('Content-Type', 'application/json');
    }

    private function setCookies(
        User $user,
        Request $request,
        ResponseInterface $response
    ): ResponseInterface {
        $paramsToEncode = [
            'userId' => $user->id(),
            'userType' => $user->type(),
            'site' => $request->getAttribute('site')
        ];
        $jwtValue = base64_encode(JWT::encode($paramsToEncode, JWT_CODE, JWT_METHOD));
        $response = FigResponseCookies::set(
            $response,
            SetCookie::create(JWT_TOKEN_NAME, $jwtValue)
                ->withPath(self::ROOT_PATH)
                ->withDomain(APP_HOST)
        );

        return $response;
    }
}
