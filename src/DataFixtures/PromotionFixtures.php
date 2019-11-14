<?php

namespace App\DataFixtures;

use App\Entity\Promotion;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class PromotionFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $promotionNames = ["Salarié", "A1", "A2", "A3", "A4", "A5"];

        for($i=0; $i<count($promotionNames); $i++) {
            $promotion = new Promotion();
            $promotion->setName($promotionNames[$i]);

            $tag = "promotion-" . $i;
            $this->addReference($tag, $promotion);
            $manager->persist($promotion);
        }

        $manager->flush();
    }
}
