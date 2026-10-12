<?php

namespace App\Service;

use App\Entity\Event;
use App\Entity\Registration;
use App\Entity\User;
use App\Enum\RegistrationStatus;
use App\Repository\RegistrationRepository;

class RegistrationService
{
    public function __construct(
        private RegistrationRepository $registrationRepository
    ) {}

    public function register(User $user, Event $event)
    {
        $registration = $this->registrationRepository->findRegistrationByUserEvent($user, $event);
        if($registration == null)
        {
            $registration = new Registration();
            $registration->setEvent($event);
            $registration->setUser($user);
        }
        if ($event->getRegistrations()->count() >= $event->getCapacity())
            $registration->setStatus(RegistrationStatus::Waitlist);
        else
            $registration->setStatus(RegistrationStatus::Confirmed);

        $this->registrationRepository->persistAndSave($registration);
    }

    public function unregister(User $user, Event $event)
    {
        $registration = $this->registrationRepository->findRegistrationByUserEvent($user, $event);
        $registration->setStatus(RegistrationStatus::Cancelled);
        $this->registrationRepository->persistAndSave($registration);
    }
}
