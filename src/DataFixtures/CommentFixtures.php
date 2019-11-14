<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use App\DataFixtures\UserFixtures;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class CommentFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = \Faker\Factory::create('fr_FR');

        for($i=0; $i<20; $i++) {
            $comment = new Comment();
            $userTag = "user-" . rand(0, 9);
            $comment
                ->setContent($faker->realText())
                ->setCreatedAt($faker->dateTimeBetween('-6 months'))
                ->setUser($this->getReference($userTag))
            ;

            if(rand(0, 1) === 1) {
                $eventTag = "event-" . rand(0, 9);
                $comment->setEvent($this->getReference($eventTag));
            } else {
                $productTag = "product-" . rand(0, 14);
                $comment->setProduct($this->getReference($productTag));
            }

            $manager->persist($comment);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            UserFixtures::class,
            EventFixtures::class,
            ProductFixtures::class,
        );
    }
}
