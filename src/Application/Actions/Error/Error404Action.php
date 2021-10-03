<?php
namespace App\Application\Actions\Error;

use Slim\Views\Twig;
use Slim\Psr7\Response;
use Psr\Http\Message\ResponseInterface;

class Error404Action
{
    private Twig $twig;
    private Response $response;

    public function __construct(Twig $twig, Response $response) {
        $this->twig = $twig;
        $this->response = $response;
    }

    public function __invoke(): ResponseInterface
    {
        return $this->twig->render(
            $this->response->withStatus(404),
            'error/404.twig'
        );
    }
}