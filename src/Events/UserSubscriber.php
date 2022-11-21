<?php

namespace App\Events;

use App\Entity\User;
use App\Entity\Badge;
use App\Events\UserTenDealsEvent;
use App\Events\UserTenCommentsEvent;
use App\Events\UserTenMarksGivenEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\BadgeRepository;

class UserSubscriber implements EventSubscriberInterface
{
    private $em;
    private $br;

    public function __construct(EntityManagerInterface $em, BadgeRepository $br){
        $this->em = $em;
        $this->br = $br;
    }

    public static function getSubscribedEvents()
    {
        return [
            UserTenDealsEvent::NAME => 'onTenDealsPosted',
            UserTenCommentsEvent::NAME => 'onTenCommentsPosted',
            UserTenMarksGivenEvent::NAME => 'onTenMarksGiven'
        ];
    }

    public function onTenDealsPosted(UserTenDealsEvent $event)
    {
        $user = $event->getUser();
        // $badge = new Badge();
        // $badge->setTitle('You post a deal');
        // $badge->setImage('');
        $badge = $this->br->findOneBy(['title' => 'Guinea pig']);
        $user->addBadge($badge);

        $this->em->persist($badge);
        $this->em->persist($user);
        $this->em->flush();
    }
    
    public function onTenCommentsPosted(UserTenCommentsEvent $event)
    {
        $user = $event->getUser();
        $badge = $this->br->findOneBy(['title' => 'Training report']);
        $user->addBadge($badge);

        $this->em->persist($badge);
        $this->em->persist($user);
        $this->em->flush();
    }

    public function onTenMarksGiven(UserTenMarksGivenEvent $event){
        $user = $event->getUser();
        $badge = $this->br->findOneBy(['title' => 'Watchers']);
        $user->addBadge($badge);

        $this->em->persist($badge);
        $this->em->persist($user);
        $this->em->flush();
    }
}
