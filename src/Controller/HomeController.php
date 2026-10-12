<?php

namespace App\Controller;

use App\Repository\EventRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;
use Twig\Environment;

#[AsController]
#[Route(path: '/', name: 'homepage', methods: ['GET'])]
class HomeController
{
    public function __invoke(Environment $twig, EventRepository $eventRepository): Response
    {

        return new Response($twig->render('home.html.twig',
        [
            'events'=> $eventRepository->findEventsPublished(),
        ]), Response::HTTP_OK);
    }
}
