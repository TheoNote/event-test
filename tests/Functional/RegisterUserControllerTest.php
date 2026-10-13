<?php

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RegisterUserControllerTest extends WebTestCase
{
    public function testRegisterUserPageIsReachable(): void
    {
        $client = static::createClient();
        $client->request('GET', '/inscription');
        self::assertResponseIsSuccessful();
        self::assertSelectorTextContains('h2', 'Rejoignez-nous');
    }
}
