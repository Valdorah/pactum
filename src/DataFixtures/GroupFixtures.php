<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Group;

class GroupFixtures extends Fixture
{
	public const GROUP_REFERENCE = 'group';
	
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);
        $g1 = new Group();
        $g1->setName("Video Game");
        $manager->persist($g1);
        
        $g2 = new Group();
        $g2->setName("High Tech");
        $manager->persist($g2);

        $manager->flush();
        
        $this->addReference(self::GROUP_REFERENCE, $g2);
    }
}
