<?php

namespace App\DataFixtures;

use App\Entity\Period;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class PeriodFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = \Faker\Factory::create('fr_FR');

        for($i=0; $i<5; $i++) {
            $period = new Period();
            $period->setTime($faker->word);

            $tag = "period-" . $i;
            $this->addReference($tag, $period);
            $manager->persist($period);
        }

        $manager->flush();
    }
}
