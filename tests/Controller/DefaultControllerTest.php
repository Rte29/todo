<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function testDefaultPage(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $this->assertResponseIsSuccessful();

        $button = $crawler->filter('.btn.btn-success.col-3');
        $this->assertEquals(1, count($button));

        $button = $crawler->filter('.btn.btn-info.col-3');
        $this->assertEquals(1, count($button));

        $button = $crawler->filter('.btn.btn-secondary.col-3');
        $this->assertEquals(1, count($button));

        $this->assertSelectorTextContains('h1', 'Bienvenue sur Todo List, l\'application vous permettant de gérer l\'ensemble de vos tâches sans effort !');
    }
}
