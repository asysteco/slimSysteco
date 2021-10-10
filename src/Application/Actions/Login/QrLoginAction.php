<?php

namespace App\Application\Actions\Login;

use Exception;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Dflydev\FigCookies\SetCookie;
use Psr\Http\Message\ResponseInterface;
use Dflydev\FigCookies\FigResponseCookies;
use App\Application\Actions\ActionResponseTrait;
use App\Application\UseCase\Login\QrLoginUseCase;

class QrLoginAction
{
    use ActionResponseTrait;

    private const SESSION_ID = 'session-id';
    private const USER_ID = 'user-id';
    private const COOKIE_SESSION = 'session';
    private const ROOT_PATH = '/';
    private const READER_ID = 4;

    private QrLoginUseCase $qrLoginUseCase;

    public function __construct(
        QrLoginUseCase $qrLoginUseCase
    ) {
        $this->qrLoginUseCase = $qrLoginUseCase;
    }

    public function __invoke(Request $request, Response $response): ResponseInterface
    {
        try {
            $params = $request->getParsedBody();
            $token = $params['adminCode'] ?? null;
            $siteOptions = $request->getAttribute('site-options');
            
            $this->qrLoginUseCase->execute($token, $siteOptions);
            $responseData = $this->successResponse();
            
            $sessionCookie = $this->setSessionValues($token);
            
        } catch (Exception $e) {
            $responseData = $this->errorResponse();
        }


        if (isset($sessionCookie)) {
            $response = FigResponseCookies::set(
                $response,
                SetCookie::create(self::COOKIE_SESSION, $sessionCookie)
                    ->withPath(self::ROOT_PATH)
                    ->withDomain(APP_HOST)
                    ->rememberForever()
            );
        }
        $response->getBody()->write(json_encode($responseData));

        return $response->withHeader('Content-Type', 'application/json');
    }

    private function setSessionValues(string $token): string
    {
        $hash = sha1($token . date('Ym'));
        $_SESSION[self::SESSION_ID] = $hash;
        $_SESSION[self::USER_ID] = self::READER_ID;

        return $hash;
    }
}
