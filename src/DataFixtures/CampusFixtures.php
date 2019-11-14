<?php

namespace App\DataFixtures;

use App\Entity\Campus;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class CampusFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $campusNames = ["Strasbourg", "Nancy", "Reims", "Arras", "Paris", "Bordeaux", "Toulouse", "Rouen", "Nice", "Pau"];

        for($i=0; $i<count($campusNames); $i++) {
            $campus = new Campus();
            $campus->setName($campusNames[$i]);

            $tag = 'campus-' . $i;
            $this->addReference($tag, $campus);
            $manager->persist($campus);
        }

        $manager->flush();
    }
}
