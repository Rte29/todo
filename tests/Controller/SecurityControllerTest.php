<?php

namespace App\Tests\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SecurityControllerTest extends WebTestCase
{
    private $client;

    public function testIfLoginIsSuccessful()
    {
        $client = static::createClient();

        /** @var UrlGeneratorInterface $urlGenerator */
        $urlGenerator = $client->getContainer()->get("router");

        $crawler = $client->request('GET', $urlGenerator->generate('login'));

        $form = $crawler->filter("form[name=login]")->form(
            [
                "email" => "admin@test.fr",
                "password" => "password"
            ]
        );

        $client->submit($form);

        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);

        $client->followRedirect();
        $this->assertRouteSame('app_default');
    }

    public function testLogout()
    {
        $client = static::createClient();

        /** @var UrlGeneratorInterface $urlGenerator */
        $urlGenerator = $client->getContainer()->get("router");

        $crawler = $client->request('GET', $urlGenerator->generate('login'));

        $form = $crawler->filter("form[name=login]")->form(
            [
                "email" => "admin@test.fr",
                "password" => "password"
            ]
        );

        $client->submit($form);

        $crawler = $client->request('GET', '/');
        $crawler->selectLink('Se déconnecter')->link();
        $this->throwException(new \Exception('app_logout'));
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}
