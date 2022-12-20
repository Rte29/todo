<?php

use App\Entity\Task;
use App\Entity\User;
use App\Repository\TaskRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TaskRepositoryTest extends WebTestCase
{
    public function testSave()
    {
        self::bootKernel();
        $taskRepository = static::getContainer()->get(TaskRepository::class);
        $userRepository = static::getContainer()->get(UserRepository::class);
        // retrieve the test users
        $user = $userRepository->findOneBy(['username' => 'anonymous']);

        $task = new Task;
        $task->setTitle('title#1');
        $task->setContent('content#1');
        $task->isIsDone(false);
        $task->setUser($user);

        $taskRepository->save($task, true);
        $this->assertEquals($task->getTitle(), $taskRepository->findOneBy(['content' => 'content#1'])->getTitle());
    }

    public function testRemove()
    {
        $client = static::createClient();
        self::bootKernel();

        $taskRepository = static::getContainer()->get(TaskRepository::class);

        // retrieve the test users
        $task = $taskRepository->findOneBy(['title' => 'title#1']);

        $taskRepository->remove($task, true);

        $task = $taskRepository->findOneBy(['title' => 'title#1']);

        $this->assertEmpty($task);
    }
}
