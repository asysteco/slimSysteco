<?php
namespace App\Application\Actions\Login;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Views\Twig;

class QrLoginTwigAction
{
    private Twig $twig;

    public function __construct(Twig $twig) {
        $this->twig = $twig;
    }

    public function __invoke(RequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        return $this->twig->render(
            $response,
            'login/qrLogin.twig'
        );
    }
}