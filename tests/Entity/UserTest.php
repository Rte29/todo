<?php

namespace App\tests\Entity;

use App\Entity\User;
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
}
