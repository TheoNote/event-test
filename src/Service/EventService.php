<?php

namespace App\Service;

use App\Entity\Event;
use App\Entity\User;
use App\Repository\EventRepository;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class EventService
{
    public function __construct(
        private EventRepository $eventRepository,
        private SluggerInterface $slugger,
        private FileUploaderService $fileUploaderService,
    ) {
    }

    public function create(Event $event, User $organizer, ?UploadedFile $file): Event
    {
        if ($file)
            $event->setImageDir($this->fileUploaderService->upload($file));

        $event->setSlug(strtolower($this->slugger->slug($event->getTitle())));
        $event->setOrganizer($organizer);

        $this->eventRepository->persistAndSave($event);
        return $event;
    }

    public function edit(Event $event, ?UploadedFile $file): Event
    {
        if ($file)
            $event->setImageDir($this->fileUploaderService->upload($file));

        $event->setSlug(strtolower($this->slugger->slug($event->getTitle())));

        $this->eventRepository->persistAndSave($event);
        return $event;
    }
}
