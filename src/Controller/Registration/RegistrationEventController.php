<?php

namespace App\Controller\Registration;

use App\Entity\User;
use App\Repository\EventRepository;
use App\Service\RegistrationService;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[AsController]
#[IsGranted('IS_AUTHENTICATED')]
#[Route('/registration/{slug}',name: 'event-register')]
class RegistrationEventController
{
    public function __invoke(string $slug, RouterInterface $router, RegistrationService $registrationService, EventRepository $eventRepository, #[CurrentUser] ?User $user)
    {
        $event =$eventRepository->findEventBySlug($slug);
        $registrationService->register($user, $event);
        return new RedirectResponse($router->generate('show-event',['slug' => $slug]));
    }
}
