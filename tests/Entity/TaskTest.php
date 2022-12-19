<?php

namespace App\Tests\Entity;

use App\Entity\Task;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;


class TaskTest extends WebTestCase
{
    public function getEntity(): Task
    {
        $user = static::getContainer()->get('doctrine.orm.entity_manager')->find(User::class, 1);

        return (new Task())
            ->setTitle('title #1')
            ->setCreatedAt(new \DateTimeImmutable())
            ->setContent('content #1')
            ->setIsDone(true)
            ->setUser($user);
    }

    public function testEntityIsValid(): void
    {
        self::bootKernel();
        $container = static::getContainer();

        $task = $this->getEntity();

        $errors = $container->get('validator')->validate($task);

        $this->assertCount(0, $errors);
    }

    public function testInvalidBlankTitle(): void
    {
        self::bootKernel();
        $container = static::getContainer();

        $task = $this->getEntity();
        $task->setTitle('');

        $errors = $container->get('validator')->validate($task);
        $this->assertCount(1, $errors);
    }

    public function testInvalidBlankContent(): void
    {
        self::bootKernel();
        $container = static::getContainer();

        $task = $this->getEntity();
        $task->setContent('');

        $errors = $container->get('validator')->validate($task);
        $this->assertCount(1, $errors);
    }
}
