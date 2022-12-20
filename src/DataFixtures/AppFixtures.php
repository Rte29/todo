<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Task;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $passwordHasher)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        $users = [];

        $admin = new User();
        $admin->setEmail('admin@test.fr');
        $admin->setUsername('admin');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setPassword($this->passwordHasher->hashPassword($admin, 'password'));

        $users[] = $admin;
        $manager->persist($admin);



        $anonymous = new User();
        $anonymous->setEmail('anonymous@test.fr');
        $anonymous->setUsername('anonymous');
        $anonymous->setRoles(['ROLE_USER']);
        $anonymous->setPassword($this->passwordHasher->hashPassword($admin, 'password'));

        $users[] = $anonymous;
        $manager->persist($anonymous);


        for ($i = 1; $i <= 15; $i++) {
            $user = new User();
            $user->setEmail($faker->email);
            $user->setUsername($faker->userName);
            $user->setRoles(['ROLE_USER']);
            $user->setPassword($this->passwordHasher->hashPassword($user, 'password'));

            $users[] = $user;
            $manager->persist($user);
        }

        $tasks = [];

        $task = new Task();
        $task->setTitle('titleTest');
        $task->setContent('contentTest');
        $task->setUser($users[mt_rand(0, count($users) - 1)]);

        $tasks[] = $task;
        $manager->persist($task);

        for ($i = 1; $i <= 15; $i++) {
            $task = new Task();
            $task->setTitle($faker->word());
            $task->setContent($faker->text);
            $task->setUser($users[mt_rand(0, count($users) - 1)]);

            $tasks[] = $task;
            $manager->persist($task);
        }


        $manager->flush();
    }
}
