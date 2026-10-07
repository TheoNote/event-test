<?php

namespace App\Controller\Event;

use App\Repository\EventRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;
use Twig\Environment;

#[AsController]
#[Route(path: '/nos-evenement', name: 'list-events')]
class ListEventController
{
    public function __invoke(Environment $twig, EventRepository $eventRepository): Response
    {
        return new Response($twig->render('event/index.html.twig', [
            'events' => $eventRepository->findEventPublished(),
        ]), Response::HTTP_OK);
    }
}
