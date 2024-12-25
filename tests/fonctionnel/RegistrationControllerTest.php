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

        // Submit registration form with test data
        $formData = [
            'registration_form[email]' => 'testuser@example.com',
            'registration_form[plainPassword]' => 'password123',
        ];

        $client->request('POST', '/register', $formData);

        // Ensure the response redirects after registration
        $this->assertResponseRedirects();

        // Follow the redirect to ensure login
        $client->followRedirect();

        $this->assertRouteSame('app_home');  // Adjust if login redirects to a different route
        $this->assertSelectorTextContains('bien inscrit!', 'User registration confirmation message');
    }
}