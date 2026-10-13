<?php

namespace App\Controller\Event;
use App\Entity\Event;
use App\Entity\User;
use App\Form\User\EventType;
use App\Repository\EventRepository;
use App\Service\EventService;
use App\Service\FileUploaderService;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\ExpressionLanguage\Expression;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\String\Slugger\SluggerInterface;
use Twig\Environment;

#[AsController]
#[IsGranted('IS_AUTHENTICATED')]
#[IsGranted(new Expression('is_granted("ROLE_ADMIN") or is_granted("ROLE_ORGANIZER")'))]
#[Route(path: '/cree-un-evenement', name: 'create-event', methods: ['GET', 'POST'])]
class CreateEventController
{
    public function __invoke(FileUploaderService $fileUploaderService ,EventService $eventService ,Environment $twig, Request $request,EventRepository $eventRepository, FormFactoryInterface $formFactory, Security $security, RouterInterface $router, SluggerInterface $slugger): Response
    {
        $event = new Event();
        $form = $formFactory->create(EventType::class, $event);
        $user = $security->getUser();

        $form->handleRequest($request);
        try {
            if ($form->isSubmitted() && $form->isValid() && $user instanceof User) {
                $eventService->create(
                    $event,
                    $user,
                    $form->get('image')->getData()
                );
                return new RedirectResponse($router->generate('list-events'));
            }
        } catch (LogicException $e){

        }

        return new Response($twig->render('event/create.html.twig', [
            'form' => $form->createView(),
        ]), Response::HTTP_OK);
    }
}
