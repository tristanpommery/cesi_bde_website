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

        $admin = new User();
        $admin
            ->setFirstName("admin")
            ->setLastName("itel")
            ->setEmail("admin.itel@viacesi.fr")
            ->setPassword('$argon2id$v=19$m=65536,t=4,p=1$bkFNRllZRVhDZTJUSlAwaQ$7Vfd1p7q9GuuqJjqqevtloUWhx84MJ9oLfhY1AUfN0U')
            ->setRoles(["ROLE_BDE"])
            ->setGenre("Terminator")
            ->setImage("https://cdn.discordapp.com/attachments/497356708218273792/644524129076248616/JOE-ELLIS-TEA.jpg")
            ->setPromotion($this->getReference('promotion-0'))
            ->setCampus($this->getReference('campus-0'))
            ->setAssociations($this->getReference('association-0'))
        ;

        $manager->persist($admin);

        for($i=0; $i<10; $i++) {
            $user = new User();
            $promotionTag = 'promotion-' . rand(0, 5);
            $campusTag = 'campus-' . rand(0, 9);
            $associationTag = 'association-' . rand(0, 4);

            $firstName = $faker->firstName();
            $lastName = $faker->lastName();
            $email = $firstName . "." . $lastName . "@viacesi.fr";
            if (rand(0, 1) == 1) {
                $genre = "Homme";
            } else {
                $genre = "Femme";
            }

            $user
                ->setFirstName($firstName)
                ->setLastName($lastName)
                ->setEmail($email)
                ->setPassword($faker->md5)
                ->setGenre($genre)
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
