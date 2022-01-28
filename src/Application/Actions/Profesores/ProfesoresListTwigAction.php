<?php

namespace App\Application\Actions\Profesores;

use Slim\Psr7\Request;
use Slim\Psr7\Response;
use App\Infrastructure\Profesor\ProfesorReaderRepositoryInterface;
use Slim\Views\Twig;

class ProfesoresListTwigAction
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
        $userType = $request->getAttribute('userType');
        $profesores = $this->profesorReaderRepository->getProfesorList();

        return $this->twig->render(
            $response,
            'profesores/profesoresListView.twig',
            [
                'title' => 'Profesores / Personal',
                'menu' => 'profesores',
                'section' => 'profesores',
                'userType' => $userType,
                'profesores' => $profesores
            ]
        );
    }
}
