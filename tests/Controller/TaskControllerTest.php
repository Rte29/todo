<?php

namespace App\Tests\Controller;

use App\Entity\Task;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TaskControllerTest extends WebTestCase
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

        $crawler = $this->client->request('GET', '/tasks');
        static::assertSame(200, $this->client->getResponse()->getStatusCode());
    }

    public function testCreateActionWithoutLoginUser()
    {
        $crawler = $this->client->request('GET', '/tasks/create');
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());

        $crawler = $this->client->followRedirect();
        $this->assertRouteSame('login');

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertEquals(1, $crawler->filter('div.alert-danger')->count());
    }

    public function testCreateActionWithLoginUser()
    {
        $this->loginUser();

        $crawler = $this->client->request('GET', '/tasks/create');
        static::assertSame(200, $this->client->getResponse()->getStatusCode());

        // Test if creation page field exists
        static::assertSame(1, $crawler->filter('input[name="task[title]"]')->count());
        static::assertSame(1, $crawler->filter('textarea[name="task[content]"]')->count());

        $form = $crawler->selectButton('Ajouter')->form();
        $form['task[title]'] = 'nouveau titre';
        $form['task[content]'] = 'nouveau contenu de tâche pour test';
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

        $task = $em->getRepository(Task::class)->findOneBy(['title' => 'nouveau titre']);

        $crawler = $this->client->request('GET', $urlGenerator->generate('task_edit', ['id' => $task->getId()]));

        static::assertSame(200, $this->client->getResponse()->getStatusCode());

        static::assertSame(1, $crawler->filter('input[name="task[title]"]')->count());
        static::assertSame(1, $crawler->filter('textarea[name="task[content]"]')->count());

        $form = $crawler->selectButton('Modifier')->form();
        $form['task[title]'] = 'modification de titre pour test';
        $form['task[content]'] = 'modification du contenu de tâche pour test';
        $this->client->submit($form);
        static::assertSame(302, $this->client->getResponse()->getStatusCode());

        $crawler = $this->client->followRedirect();
        static::assertSame(200, $this->client->getResponse()->getStatusCode());
    }

    public function testToggleTaskAction(): void
    {
        $this->loginUser();

        $urlGenerator = $this->client->getContainer()->get('router');
        $em = $this->client->getContainer()->get('doctrine.orm.entity_manager');

        $task = $em->getRepository(Task::class)->findOneBy(['title' => 'modification de titre pour test']);

        $crawler = $this->client->request('GET', $urlGenerator->generate('task_toggle', ['id' => $task->getId()]));

        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());

        $crawler = $this->client->followRedirect();

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertEquals(1, $crawler->filter('div.alert-success')->count());
    }

    public function testDeleteTaskAction()
    {
        $this->loginUser();

        $urlGenerator = $this->client->getContainer()->get('router');
        $em = $this->client->getContainer()->get('doctrine.orm.entity_manager');

        $task = $em->getRepository(Task::class)->findOneBy(['title' => 'modification de titre pour test']);

        $crawler = $this->client->request('GET', $urlGenerator->generate('task_delete', ['id' => $task->getId()]));

        static::assertSame(302, $this->client->getResponse()->getStatusCode());
        $crawler = $this->client->followRedirect();
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        static::assertSame("Génial ! La tâche a bien été supprimée.", $crawler->filter('div.alert.alert-success')->text());
    }
}
