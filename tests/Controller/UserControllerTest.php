<?php

namespace App\Tests\Controller;

use App\Tests\Controller\SecurityControllerTest;
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

        // Test if creation page field exists
        //static::assertSame(1, $crawler->filter('input[name="user[username]"]')->count());
        //static::assertSame(1, $crawler->filter('input[name="user[password][first]"]')->count());
        //static::assertSame(1, $crawler->filter('input[name="user[password][second]"]')->count());
        //static::assertSame(1, $crawler->filter('input[name="user[email]"]')->count());
        //static::assertSame(2, $crawler->filter('input[name="user[roles][]"]')->count());

        $form = $crawler->selectButton('Ajouter')->form();
        $form['user[username]'] = 'newuser';
        $form['user[password][first]'] = 'test';
        $form['user[password][second]'] = 'test';
        $form['user[email]'] = 'test@test.fr';
        $form['user[roles][0]']->tick();
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

        $user = $em->getRepository(User::class)->findOneBy(['username' => 'admin']);

        $crawler = $this->client->request('GET', $urlGenerator->generate('user_edit', ['id' => $user->getId()]));

        static::assertSame(200, $this->client->getResponse()->getStatusCode());

        $form = $crawler->selectButton('Modifier')->form();
        $form['task[title]'] = 'nouveau titre';
        $form['task[content]'] = 'nouveau contenu de tâche pour test';
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

        $task = $em->getRepository(Task::class)->findOneBy(['title' => 'nouveau titre']);

        $crawler = $this->client->request('GET', $urlGenerator->generate('task_delete', ['id' => $task->getId()]));

        static::assertSame(302, $this->client->getResponse()->getStatusCode());
    }
}
