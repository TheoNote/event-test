<?php

namespace App\Tests\Functional;

use App\Entity\Category;
use App\Entity\Event;
use App\Enum\EventStatus;
use App\Repository\EventRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class EditEventControllerTest extends WebTestCase
{
    public function testOrganizerCanEditEvent(): void
    {
        $client = static::createClient();

        $eventRepository = static::getContainer()
            ->get(EventRepository::class);

        $userRepository = static::getContainer()
            ->get(UserRepository::class);

        $organizer = $userRepository->findUserByEmail('organizer@eventhub.test');

        $client->loginUser($organizer);

        $entityManager = static::getContainer()
            ->get(EntityManagerInterface::class);

        $category = $entityManager
            ->getRepository(Category::class)
            ->findOneBy([]);

        self::assertInstanceOf(Category::class, $category);


        $event = (new Event());
        $event->setTitle('EventHub #Test');
        $event->setSlug('event-test');
        $event->setDescription('Une description');
        $start = new \DateTimeImmutable('+'.(1).' days 18:00');
        $event->setStartAt($start);
        $event->setEndAt($start->modify('5 hours'));
        $event->setStatus(EventStatus::Draft);
        $event->setOrganizer($organizer);
        $event->setCategory($category);;
        $event->setCapacity(5);

        $eventRepository->persistAndSave($event);

        $crawler = $client->request('GET', '/modifier-un-event/'.$event->getSlug());

        self::assertResponseIsSuccessful();

        $title = 'Test edit événement fonctionnel';

        $form = $crawler->selectButton('Enregistrer')->form([
            'event[title]' => $title,
            'event[description]' => 'Description de test fonctionnel.',
            'event[startAt]' => '2026-11-10T10:00',
            'event[endAt]' => '2026-11-10T12:00',
            'event[capacity]' => 20,
            'event[category]' => (string) $category->getId(),
            'event[status]' => EventStatus::Draft->value,
        ]);

        $client->submit($form);

        self::assertSame('POST', $client->getRequest()->getMethod());

        self::assertResponseRedirects();

        $event = $entityManager
            ->getRepository(Event::class)
            ->findOneBy(['title' => $title]);

        self::assertInstanceOf(Event::class, $event);
        self::assertSame($organizer->getId(), $event->getOrganizer()->getId());
    }
}
