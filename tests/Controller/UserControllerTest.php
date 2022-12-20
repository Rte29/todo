<?php

namespace App\Tests\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    private $client;

    public function setUp(): void
    {
        $this->client = static::createClient();
    }

    public function loginUser(): void
    {
        $crawler = $this->client->request('GET', '/login');
        $form = $crawler->selectButton('Validez')->form();
        $this->client->submit($form, [
            "email" => "admin@test.fr",
            "password" => "password"
        ]);
    }

    public function testListAction()
    {
        $this->loginUser();

        $crawler = $this->client->request('GET', '/users');
        static::assertSame(200, $this->client->getResponse()->getStatusCode());
        static::assertSame("Liste des utilisateurs", $crawler->filter('h1')->text());
    }

    public function testCreateAction()
    {
        $crawler = $this->client->request('GET', '/users/create');
        static::assertSame(200, $this->client->getResponse()->getStatusCode());

        static::assertSame(1, $crawler->filter('input[name="user[username]"]')->count());
        static::assertSame(1, $crawler->filter('input[name="user[plainPassword][first]"]')->count());
        static::assertSame(1, $crawler->filter('input[name="user[plainPassword][second]"]')->count());
        static::assertSame(1, $crawler->filter('input[name="user[email]"]')->count());


        $form = $crawler->selectButton('Ajouter')->form();
        $form['user[username]'] = 'newuser';
        $form['user[plainPassword][first]'] = 'password';
        $form['user[plainPassword][second]'] = 'password';
        $form['user[email]'] = 'test@test.fr';

        $this->client->submit($form);
        static::assertSame(302, $this->client->getResponse()->getStatusCode());

        $crawler = $this->client->followRedirect();
        static::assertSame(200, $this->client->getResponse()->getStatusCode());
    }

    public function testEditAction()
    {
        $this->loginUser();

        $urlGenerator = $this->client->getContainer()->get('router');
        $em = $this->client->getContainer()->get('doctrine.orm.entity_manager');

        $user = $em->getRepository(User::class)->findOneBy(['username' => 'newuser']);

        $crawler = $this->client->request('GET', $urlGenerator->generate('user_edit', ['id' => $user->getId()]));

        static::assertSame(200, $this->client->getResponse()->getStatusCode());

        static::assertSame(1, $crawler->filter('input[name="user[username]"]')->count());
        static::assertSame(1, $crawler->filter('input[name="user[plainPassword][first]"]')->count());
        static::assertSame(1, $crawler->filter('input[name="user[plainPassword][second]"]')->count());
        static::assertSame(1, $crawler->filter('input[name="user[email]"]')->count());

        $form = $crawler->selectButton('Modifier')->form();
        $form['user[username]'] = 'username2';
        $form['user[plainPassword][first]'] = 'password';
        $form['user[plainPassword][second]'] = 'password';
        $form['user[email]'] = 'email@test.fr';

        $this->client->submit($form);
        static::assertSame(302, $this->client->getResponse()->getStatusCode());

        $crawler = $this->client->followRedirect();
        static::assertSame(200, $this->client->getResponse()->getStatusCode());
    }

    public function testDeleteTaskAction()
    {
        $this->loginUser();

        $urlGenerator = $this->client->getContainer()->get('router');
        $em = $this->client->getContainer()->get('doctrine.orm.entity_manager');

        $task = $em->getRepository(User::class)->findOneBy(['username' => 'username2']);

        $crawler = $this->client->request('GET', $urlGenerator->generate('user_delete', ['id' => $task->getId()]));

        static::assertSame(302, $this->client->getResponse()->getStatusCode());
        $crawler = $this->client->followRedirect();
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }
}
