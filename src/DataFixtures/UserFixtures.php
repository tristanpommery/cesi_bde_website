<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\DataFixtures\CampusFixtures;
use App\DataFixtures\PromotionFixtures;
use App\DataFixtures\AssociationFixtures;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class UserFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = \Faker\Factory::create('fr_FR');

        for($i=0; $i<10; $i++) {
            $user = new User();
            $promotionTag = 'promotion-' . rand(0, 5);
            $campusTag = 'campus-' . rand(0, 9);
            $associationTag = 'association-' . rand(0, 4);

            $firstName = $faker->firstName();
            $lastName = $faker->lastName();
            $email = $firstName . "." . $lastName . "@viacesi.fr";

            $user
                ->setFirstName($firstName)
                ->setLastName($lastName)
                ->setEmail($email)
                ->setPassword($faker->md5)
                ->setGenre($faker->fileExtension)
                ->setImage($faker->imageUrl(300, 300))
                ->setPromotion($this->getReference($promotionTag))
                ->setCampus($this->getReference($campusTag))
                ->setAssociations($this->getReference($associationTag))
            ;

            $tag = "user-" . $i;
            $this->addReference($tag, $user);
            $manager->persist($user);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            PromotionFixtures::class,
            CampusFixtures::class,
            AssociationFixtures::class,
        );
    }
}
