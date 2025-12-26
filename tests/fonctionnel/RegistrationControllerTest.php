<?php

namespace App\Tests\fonctionnel;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class RegistrationControllerTest extends WebTestCase
{
    public function testRegisterPageIsAccessible(): void
    {
        $client = static::createClient();

        $client->request('GET', '/register');

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertSelectorExists('form');  // Ensure that a form is present on the page
    }

    public function testUserRegistration(): void
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/register');

        $form = $crawler->filter('form')->form([
            'registration_form[email]' => 'testuser@example.com',
            'registration_form[plainPassword]' => 'password123',
        ]);

        $client->submit($form);

        $this->assertResponseRedirects();

        $client->followRedirect();

        $this->assertRouteSame('app_home');
        $this->assertSelectorTextContains('body', 'IH-AR Luxury Hotel');
    }
}
