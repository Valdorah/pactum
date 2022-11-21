<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Badge;

class BadgeFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);
        $badge1 = new Badge();
        $badge1->setTitle('Guinea pig');
        $badge1->setDescription('You post 10 deals !');
        $badge1->setImage('');

        $badge2 = new Badge();
        $badge2->setTitle('Training report');
        $badge2->setDescription('You have posted 10 comments !');
        $badge2->setImage('');

        $badge3 = new Badge();
        $badge3->setTitle('Watchers');
        $badge3->setDescription('You voted for 10 deals !');
        $badge3->setImage('');

        $manager->persist($badge1);
        $manager->persist($badge2);
        $manager->persist($badge3);

        $manager->flush();
    }
}
