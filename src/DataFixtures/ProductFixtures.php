<?php

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class ProductFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = \Faker\Factory::create('fr_FR');

        for($i=0; $i<15; $i++) {
            $product = new Product();
            $categoryTag = "category-" . rand(0, 4);
            $product
                ->setName($faker->sentence())
                ->setDescription($faker->paragraph())
                ->setPrice($faker->randomFloat(2, 5, 50))
                ->setStock($faker->randomNumber(2))
                ->setSoldCount($faker->randomNumber(2))
                ->setImage($faker->imageUrl())
                ->setCategory($this->getReference($categoryTag))
            ;

            if(rand(0, 1) === 1) {
                $associationTag = "association-" . rand(0, 4);
                $product->setAssociation($this->getReference($associationTag));
            }

            $tag = "product-" . $i;
            $this->addReference($tag, $product);
            $manager->persist($product);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            CategoryFixtures::class,
            AssociationFixtures::class,
        );
    }
}
