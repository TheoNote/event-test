<?php

namespace App\Controller\Event;

use App\Repository\EventRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Twig\Environment;

#[Route('/evenement/{slug}',name: 'show-event')]
class ShowEventController
{
    public function __invoke(string $slug, Environment $twig, EventRepository $eventRepository): Response
    {
        return new Response($twig->render('event/show.html.twig', [
            'event' => $eventRepository->findEventBySlug($slug),
        ]), Response::HTTP_OK);
    }
}
