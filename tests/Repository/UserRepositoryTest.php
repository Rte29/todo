<?php

namespace App\Tests\Repository;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;

class UserRepositoryTest extends WebTestCase
{
    public function testUpgradePassword()
    {
        self::bootKernel();
        $userRepository = static::getContainer()->get(UserRepository::class);
        // retrieve the test users
        $user = $userRepository->findOneBy(['username' => 'anonymous']);

        $userRepository->upgradePassword($user, 'test');

        $this->assertEquals($user->getPassword(), $userRepository->findOneBy(['username' => 'anonymous'])->getPassword());
    }
}
