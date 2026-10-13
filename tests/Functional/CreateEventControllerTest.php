<?php

namespace App\Tests\Functional;

use App\Entity\Category;
use App\Entity\Event;
use App\Enum\EventStatus;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CreateEventControllerTest extends WebTestCase
{
    public function testCreateEventPageIsReachable()
    {
        $client = static::createClient();
        $userRepository = static::getContainer()
            ->get(UserRepository::class);

        $organizer = $userRepository->findUserByEmail('organizer@eventhub.test');

        $client->loginUser($organizer);

        $client->request('GET', '/cree-un-evenement');
        self::assertResponseIsSuccessful();
        self::assertSelectorTextContains('h2', 'Créer un événement');
    }

    public function testOrganizerCanCreateEvent(): void
    {
        $client = static::createClient();

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

        $title = 'Test événement fonctionnel';

        $crawler = $client->request('GET', '/cree-un-evenement');

        self::assertResponseIsSuccessful();

        $form = $crawler->selectButton('Créer')->form([
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
