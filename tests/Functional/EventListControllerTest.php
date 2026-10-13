<?php

namespace App\Tests\Functional;

use App\Repository\EventRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class EventListControllerTest extends WebTestCase
{
    public function testEventListPageIsReachable(): void
    {
        $client = static::createClient();
        $client->request('GET', '/nos-evenement');
        self::assertResponseIsSuccessful();
        self::assertSelectorTextContains('h1', 'Nos événements');
    }
}
