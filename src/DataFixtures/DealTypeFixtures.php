<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\DealType;

class DealTypeFixtures extends Fixture
{
	public const DEAL_TYPE_REFERENCE = 'deal-type';
	
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);
        $t1 = new DealType();
        $t1->setTypeName("Deal");
        $manager->persist($t1);
        
        $t2 = new DealType();
        $t2->setTypeName("Promo");
        $manager->persist($t2);

        $manager->flush();
        
        $this->addReference(self::DEAL_TYPE_REFERENCE, $t1);
    }
}
