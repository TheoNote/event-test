<?php

namespace App\Controller\Event;

use App\Entity\User;
use App\Form\User\EventType;
use App\Repository\EventRepository;
use App\Repository\RegistrationRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\ExpressionLanguage\Expression;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\String\Slugger\SluggerInterface;
use Twig\Environment;

#[AsController]
#[IsGranted('IS_AUTHENTICATED')]
#[IsGranted(new Expression('is_granted("ROLE_ADMIN") or is_granted("ROLE_ORGANIZER")'))]
#[Route('/Organisateur',name: 'list-organizer-event')]
class ListOrganizerEventController
{
    public function __invoke(Environment $twig, EventRepository $eventRepository, #[CurrentUser] ?User $user): Response
    {
        return new Response($twig->render('event/index.html.twig', [
            'events' => $eventRepository->findEventsByOrganizer($user),
        ]), Response::HTTP_OK);
    }
}
