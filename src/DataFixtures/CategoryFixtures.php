<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $categoryNames = ["T-Shirts", "Pulls", "Accessoires", "Goodies", "Trébuchets"];

        for($i=0; $i<count($categoryNames); $i++) {
            $category = new Category();
            $category->setName($categoryNames[$i]);

            $tag = "category-" . $i;
            $this->addReference($tag, $category);
            $manager->persist($category);
        }

        $manager->flush();
    }
}
