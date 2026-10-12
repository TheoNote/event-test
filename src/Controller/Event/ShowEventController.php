<?php

namespace App\Controller\Event;

use App\Entity\Registration;
use App\Entity\User;
use App\Enum\EventStatus;
use App\Enum\RegistrationStatus;
use App\Repository\EventRepository;
use phpDocumentor\Reflection\Types\Array_;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Twig\Environment;

#[Route('/evenement/{slug}',name: 'show-event')]
class ShowEventController
{
    public function __invoke(string $slug, Environment $twig, EventRepository $eventRepository, Security $security, #[CurrentUser] ?User $user): Response
    {
        $event =$eventRepository->findEventBySlug($slug);
        if ($event === null ||
            (
                $event->getStatus() !== EventStatus::Published
                && $event->getOrganizer() !== $user
                && !$security->isGranted('ROLE_ADMIN')
            )
        )
        {
            throw new NotFoundHttpException('Event not found');
        }

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
