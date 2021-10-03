<?php

namespace App\Application\Actions\Profesores;

use Slim\Psr7\Request;
use Slim\Psr7\Response;
use App\Infrastructure\Profesor\ProfesorReaderRepositoryInterface;
use Slim\Views\Twig;

class ProfesoresAddTwigAction
{
    private Twig $twig;
    private ProfesorReaderRepositoryInterface $profesorReaderRepository;

    public function __construct(
        Twig $twig,
        ProfesorReaderRepositoryInterface $profesorReaderRepository
    ) {
        $this->twig = $twig;
        $this->profesorReaderRepository = $profesorReaderRepository;
    }

    public function __invoke(Request $request, Response $response): Response
    {
        $user = $request->getAttribute('user');
        $profesores = $this->profesorReaderRepository->getProfesorList();

        return $this->twig->render(
            $response,
            'profesores/profesoresAddView.twig',
            [
                'title' => 'Registrar personal',
                'menu' => 'profesores',
                'section' => 'add',
                'user' => $user,
                'profesores' => $profesores
            ]
        );
    }
}
