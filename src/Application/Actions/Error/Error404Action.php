<?php
namespace App\Application\Actions\Error;

use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Response;
use Twig\Environment;

class Error404Action
{
    private Environment $twig;
    private Response $response;

    public function __construct(Environment $twig, Response $response) {
        $this->twig = $twig;
        $this->response = $response;
    }

    public function __invoke(): ResponseInterface
    {
        $this->response->getBody()->write(
            $this->twig->render(
                'error/404.twig'
            )
        );

        return $this->response->withStatus(404);
    }
}