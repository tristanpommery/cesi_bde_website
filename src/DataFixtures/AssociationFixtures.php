<?php

namespace App\DataFixtures;

use App\Entity\Association;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AssociationFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = \Faker\Factory::create('fr_FR');
        $associationNames = ["Cesi LAN", "Game on Desk", "PiXia", "1D", "Cesi Pêche & Tradition"];

        for($i=0; $i<count($associationNames); $i++) {
            $association = new Association();
            $association
                ->setName($associationNames[$i])
                ->setDescription($faker->sentence())
                ->setImage($faker->imageUrl(300, 300))
            ;

            $tag = "association-" . $i;
            $this->addReference($tag, $association);
            $manager->persist($association);
        }

        $manager->flush();
    }
}
