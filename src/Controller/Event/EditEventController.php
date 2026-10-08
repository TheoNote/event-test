<?php

namespace App\Controller\Event;

use App\Form\User\EventType;
use App\Repository\EventRepository;
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
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\String\Slugger\SluggerInterface;
use Twig\Environment;

#[AsController]
#[IsGranted('IS_AUTHENTICATED')]
#[IsGranted(new Expression('is_granted("ROLE_ADMIN") or is_granted("ROLE_ORGANIZER")'))]
#[Route('/modifier-un-event/{slug}',name: 'edit-event')]
class EditEventController
{
    public function __invoke(string $slug, Environment $twig, Request $request,EventRepository $eventRepository, FormFactoryInterface $formFactory, Security $security, RouterInterface $router, SluggerInterface $slugger): Response
    {
        $event = $eventRepository->findEventBySlug($slug);
        if (null === $event)
        {
            throw new NotFoundHttpException('Evénement introuvable.');
        }
        if (!($security->isGranted('ROLE_ADMIN') || $event->getOrganizer()->getId() === $security->getUser()->getId())){
            throw new AccessDeniedHttpException("Accès non autorisé");
        }

        $form = $formFactory->create(EventType::class, $event);
        $form->handleRequest($request);
        try {
            if ($form->isSubmitted() && $form->isValid()) {
                $event->setSlug(strtolower($slugger->slug($event->getTitle())));

                $event = $form->getData();
                $eventRepository->persistAndSave($event);
                //return new RedirectResponse($router->generate('list-events'));
                return new RedirectResponse($router->generate('edit-event',['slug' => $event->getSlug()]));
            }
        } catch (LogicException $e){

        }

        return new Response($twig->render('event/create.html.twig', [
            'form' => $form->createView(),
            'event' => $event,
        ]), Response::HTTP_OK);
    }
}
