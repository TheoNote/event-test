<?php

namespace App\Controller\Event;

use App\Entity\Registration;
use App\Enum\RegistrationStatus;
use App\Repository\EventRepository;
use phpDocumentor\Reflection\Types\Array_;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Twig\Environment;

#[Route('/evenement/{slug}',name: 'show-event')]
class ShowEventController
{
    public function __invoke(string $slug, Environment $twig, EventRepository $eventRepository): Response
    {
        $event =$eventRepository->findEventBySlug($slug);
        $confirmedCount = $event->getRegistrations()
            ->filter(
                fn(Registration $registration) =>
                    $registration->getStatus() === RegistrationStatus::Confirmed
            )
            ->count();
        return new Response($twig->render('event/show.html.twig', [
            'event' => $event,
            'confirmedCount' => $confirmedCount,
        ]), Response::HTTP_OK);
    }
}
