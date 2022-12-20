<?php

namespace App\tests\Entity;

use App\Entity\User;
use App\Entity\Task;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserTest extends WebTestCase
{

    public function getEntity(): User
    {
        return (new User())
            ->setEmail('email@test.fr')
            ->setRoles(["ROLE_ADMIN"])
            ->setPassword('password')
            ->setUsername('username #1');
    }

    public function testEntityIsValid(): void
    {
        self::bootKernel();
        $container = static::getContainer();

        $user = $this->getEntity();

        $errors = $container->get('validator')->validate($user);

        $this->assertCount(0, $errors);
    }

    public function testInvalidBlankEmail(): void
    {
        self::bootKernel();
        $container = static::getContainer();

        $user = $this->getEntity();
        $user->setEmail('');

        $errors = $container->get('validator')->validate($user);
        $this->assertCount(1, $errors);
    }

    public function testInvalidEmail(): void
    {
        self::bootKernel();
        $container = static::getContainer();

        $user = $this->getEntity();
        $user->setEmail('email #1');

        $errors = $container->get('validator')->validate($user);
        $this->assertCount(1, $errors);
    }
    public function testRemoveTask()
    {
        $user = new User();
        // If there is not the Task in the ArrayCollection
        static::assertInstanceOf(User::class, $user->removeTask(new Task()));
        static::assertEmpty($user->getTasks());

        // If there is the Task in the ArrayCollection
        $task = new Task();
        $user->addTask($task);
        $user->removeTask($task);
        static::assertEmpty($user->getTasks());
        static::assertInstanceOf(User::class, $user->removeTask(new Task()));
    }
}
