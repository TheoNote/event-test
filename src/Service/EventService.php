<?php

namespace App\Service;

use App\Entity\Event;
use App\Entity\User;
use App\Repository\EventRepository;
use Symfony\Component\String\Slugger\SluggerInterface;

class EventService
{
    public function __construct(
        private EventRepository $eventRepository,
        private SluggerInterface $slugger,
    ) {
    }

    public function create(Event $event, User $organizer): Event
    {
        $event->setSlug(strtolower($this->slugger->slug($event->getTitle())));
        $event->setOrganizer($organizer);

        $this->eventRepository->persistAndSave($event);
        return $event;
    }

    public function edit(Event $event): Event
    {
        $event->setSlug(strtolower($this->slugger->slug($event->getTitle())));

        $this->eventRepository->persistAndSave($event);
        return $event;
    }
}
