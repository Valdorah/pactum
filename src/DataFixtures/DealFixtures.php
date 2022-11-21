<?php

namespace App\DataFixtures;

use App\Entity\Deal;
use App\Entity\Comment;
use App\DataFixtures\UserFixtures;
use App\DataFixtures\GroupFixtures;
use App\DataFixtures\DealTypeFixtures;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class DealFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $user = $this->getReference(UserFixtures::USER_REFERENCE);
        // $product = new Product();
        // $manager->persist($product);
        $comment = new Comment();
        $comment->setText("This is an awesome techonlogie !");
        $comment->setUser($user);

        $manager->persist($comment);

        $deal = new Deal();
        $deal->setTitle("Title Fixtures");
        $deal->setDescription("Description Fixtures");
        $deal->setUrl("http://example.org/");
        $deal->setImage("https://symfony.com/logos/symfony_black_03.png");
        $deal->setPrice(4.2);
        $deal->setNormalPrice(5.0);
        $deal->setDeliveryCost(0.0);
        $deal->setDiscountCode("symfony");
        $deal->addComment($comment);
        $deal->addGroup($this->getReference(GroupFixtures::GROUP_REFERENCE));
        $deal->setType($this->getReference(DealTypeFixtures::DEAL_TYPE_REFERENCE));
        $deal->setExpired(false);

        $user->addPostedDeal($deal);
        
        $manager->persist($deal);
        $manager->flush();
        
    }
    
    public function getDependencies()
    {
        return array(
            UserFixtures::class,
            GroupFixtures::class,
            DealTypeFixtures::class
        );
    }
}
