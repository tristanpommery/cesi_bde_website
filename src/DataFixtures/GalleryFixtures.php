<?php

namespace App\DataFixtures;

use App\Entity\Gallery;
use App\DataFixtures\UserFixtures;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class GalleryFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = \Faker\Factory::create('fr_FR');

        for($i=0; $i<30; $i++) {
            $gallery = new Gallery();
            $eventTag = "event-" . rand(0, 9);
            $authorTag = "user-" . rand(0, 9);
            $gallery
                ->setImage($faker->imageUrl())
                ->setEvent($this->getReference($eventTag))
                ->setAuthor($this->getReference($authorTag))
            ;

            for($j=0; $j<rand(0, 5); $j++) {
                $userTag = "user-" .  rand(0, 9);
                $gallery->addUser($this->getReference($userTag));
            }

            $tag = 'gallery-' . $i;
            $this->addReference($tag, $gallery);
            $manager->persist($gallery);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            EventFixtures::class,
            UserFixtures::class,
        );
    }
}
