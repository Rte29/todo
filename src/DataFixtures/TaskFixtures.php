<?php

namespace App\DataFixtures;

use App\Entity\Task;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Faker;

class TaskFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');
        for ($i = 1; $i <= 15; $i++) {
            $task = new Task();
            $task->setTitle($faker->word());
            $task->setContent($faker->text);
            $manager->persist($task);
        }


        $manager->flush();
    }
}
