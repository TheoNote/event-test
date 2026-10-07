<?php

namespace App\Controller\Event;
use App\Entity\Event;
use App\Entity\User;
use App\Form\Event\EventType;
use App\Repository\EventRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;
use Twig\Environment;

#[AsController]
#[Route(path: '/event', name: 'create-event', methods: ['GET', 'POST'])]
class CreateEventController
{
    public function __invoke(Environment $twig, Request $request,EventRepository $eventRepository, FormFactoryInterface $formFactory, Security $security): Response
    {
        $event = new Event();
        $event->setSlug("a");
        $form = $formFactory->create(EventType::class, $event);
        $user = $security->getUser();

        $form->handleRequest($request);
        try {
            if ($form->isSubmitted() && $form->isValid() && $user instanceof User) {

                $title = $event->getTitle();
                $slug = trim(strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', iconv('UTF-8', 'ASCII//TRANSLIT', $title))), '-');
                $event->setSlug($slug);
                $event->setOrganizer($user);

                $event = $form->getData();
                $eventRepository->persistAndSave($event);
            }
        } catch (LogicException $e){

        }

        return new Response($twig->render('event/create.html.twig', [
            'form' => $form->createView(),
        ]), Response::HTTP_OK);
    }
}
