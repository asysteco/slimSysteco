<?php

namespace App\Application\Actions\Profesores;

use App\Infrastructure\Profesor\ProfesorReaderRepositoryInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Twig\Environment;

class ProfesoresListTwigAction
{
    private Environment $twig;
    private ProfesorReaderRepositoryInterface $profesorReaderRepository;

    public function __construct(
        Environment $twig,
        ProfesorReaderRepositoryInterface $profesorReaderRepository
    ) {
        $this->twig = $twig;
        $this->profesorReaderRepository = $profesorReaderRepository;
    }

    public function __invoke(RequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $profesores = $this->profesorReaderRepository->getProfesorList();

        $response->getBody()->write(
            $this->twig->render(
                'profesores/profesoresListView.twig',
                [
                    'title' => 'Profesores / Personal',
                    'profesores' => $profesores
                ]
            )
        );

        return $response;
    }
}
