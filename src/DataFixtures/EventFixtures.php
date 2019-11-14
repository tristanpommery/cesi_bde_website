<?php

namespace App\DataFixtures;

use App\Entity\Event;
use App\DataFixtures\UserFixtures;
use App\DataFixtures\PeriodFixtures;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class EventFixtures extends Fixture  implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = \Faker\Factory::create('fr_FR');

        for($i=0; $i<10; $i++) {
            $event = new Event();
            $event
                ->setName($faker->sentence())
                ->setDate($faker->dateTimeBetween('-6 months'))
                ->setDescription($faker->paragraph())
                ->setImage($faker->imageUrl())
                ->setPrice($faker->randomFloat(2, 0, 30))
                ->setDuration($faker->word)
                ->setLocalization($faker->city)
            ;

            if(rand(0, 1) === 1) {
                $periodTag = "period-" . rand(0, 4);
                $event->setPeriod($this->getReference($periodTag));
            }

            for($j=0; $j<rand(0, 5); $j++) {
                $userTag = "user-" .  rand(0, 9);
                $event->addUser($this->getReference($userTag));
            }

            $tag = "event-" . $i;
            $this->addReference($tag, $event);
            $manager->persist($event);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            PeriodFixtures::class,
            UserFixtures::class,
        );
    }
}
