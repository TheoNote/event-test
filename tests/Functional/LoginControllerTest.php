<?php

namespace App\Tests\Functional;

use App\Entity\Event;
use App\Enum\EventStatus;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LoginControllerTest extends WebTestCase
{
    public function testLoginPageIsReachable(): void
    {
        $client = static::createClient();
        $client->request('GET', '/login');
        self::assertResponseIsSuccessful();
        self::assertSelectorTextContains('h1', 'Connexion');
    }

    public function testLoginAsUser(): void
    {

        $client = static::createClient();

        $crawler = $client->request('GET', '/login');

        self::assertResponseIsSuccessful();
        $form = $crawler->selectButton('connecter')->form([
            'email' => 'user@eventhub.test',
            'password' => 'azerty',
        ]);

        $client->submit($form);

        self::assertResponseRedirects('/');
        $client->followRedirect();
        self::assertResponseIsSuccessful();
    }
}
