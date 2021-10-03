<?php
declare(strict_types=1);

namespace App\Application\Middleware;

use App\Infrastructure\Profesor\ProfesorReaderRepositoryInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Server\MiddlewareInterface as Middleware;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;


class LoginUserMiddleware implements Middleware
{
    private ProfesorReaderRepositoryInterface $profesorReaderRepository;

    public function __construct(ProfesorReaderRepositoryInterface $profesorReaderRepository)
    {
        $this->profesorReaderRepository = $profesorReaderRepository;
    }
    public function process(Request $request, RequestHandler $handler): Response
    {
        $user = $this->profesorReaderRepository->getUserById($_SESSION['user-id']);
        
        $request = $request->withAttribute('user', $user);
        $request = $request->withAttribute('session', $_SESSION);

        return $handler->handle($request);
    }
}