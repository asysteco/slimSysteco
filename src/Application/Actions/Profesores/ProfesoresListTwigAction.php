<?php

namespace App\Application\Actions\Profesores;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Twig\Environment;

class ProfesoresListTwigAction
{
    private Environment $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    public function __invoke(RequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $response->getBody()->write(
            $this->twig->render(
                'main/base-includes.html.twig'
            )
        );

        return $response;
    }
}
