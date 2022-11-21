<?php

namespace App\Controller;

use App\Entity\Deal;
use App\Entity\Comment;
use App\Entity\Rate;
use App\Entity\User;
use App\Form\DealForm;
use App\Form\CommentType;
use App\Repository\DealRepository;
use App\Repository\RateRepository;
use App\Repository\UserRepository;
use App\Repository\BadgeRepository;
use App\Events\UserTenDealsEvent;
use App\Events\UserTenCommentsEvent;
use App\Events\UserTenMarksGivenEvent;
use App\Events\UserSubscriber;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\EventDispatcher\EventDispatcher;

class DealController extends AbstractController
{
    /**
     * @Route("/deals", name="app_deals")
     */
    public function deals(DealRepository $repo, Request $request)
    {
        $search = $request->query->get('search');

        if (!empty($search)) {
            $deals = $repo->search($search);
            dump($deals);
        } else {
            $deals = $repo->getHotDeals('Deal');
        }

        return $this->render('deal/index.html.twig', [
            'title' => 'Hot deals',
            'deals' => $deals,
        ]);
    }

    /**
     * @Route("/promos", name="app_promos")
     */
    public function promos(DealRepository $repo)
    {
        $deals = $repo->getHotDeals('Promo');
        return $this->render('deal/index.html.twig', [
            'title' => 'Hot promos',
            'deals' => $deals,
        ]);
    }

    /**
     * @IsGranted("ROLE_USER")
     * @Route("/create-deal", name="create_deal")
     */

    public function createDeal(Request $request, BadgeRepository $badgeRepo)
    {
        $deal = new Deal();
        $form = $this->createForm(DealForm::class, $deal);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $deal = $form->getData();

            $deal->setExpired(false);
            
            $this->getUser()->addPostedDeal($deal);

            // ... perform some action, such as saving the task to the database
            // for example, if Task is a Doctrine entity, save it!
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($deal);
            $entityManager->flush();

            if(count($this->getUser()->getPostedDeals()) == 10){
                $dispatcher = new EventDispatcher();
                $event = new UserTenDealsEvent($this->getUser());
                $subscriber = new UserSubscriber($entityManager, $badgeRepo);
                $dispatcher->addSubscriber($subscriber);
                
                $dispatcher->dispatch($event, UserTenDealsEvent::NAME);
            }
            
            return $this->redirectToRoute('home');
        }

        return $this->render('deal/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/deal/{id}", name="deal_details", requirements={"id"="\d+"})
     */
    public function dealDetails(Request $request, int $id, DealRepository $repo, BadgeRepository $badgeRepo)
    {
        $isVoted = false;
        $deal = $this->getDoctrine()
        ->getRepository(Deal::class)
        ->find($id);
        
        $comments = $this->getDoctrine()
        ->getRepository(Comment::class)
        ->findBy(
            ['deal' => $deal],
            ['postedAt' => 'DESC']
        );
        
        $user = $this->getUser();
        
        if(isset($user)){
            $isVoted = self::getSavedDeals($user, $id);
        }

        $comment = new Comment();
        $comment->setUser($user);
        $comment->setDeal($deal);
        
        $form = $this->createForm(CommentType::class, $comment);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $comment = $form->getData();

            // ... perform some action, such as saving the task to the database
            // for example, if Task is a Doctrine entity, save it!
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($comment);
            $entityManager->flush();

            dump(count($this->getUser()->getComments()));
            if(count($this->getUser()->getComments()) == 10){
                $dispatcher = new EventDispatcher();
                $event = new UserTenCommentsEvent($this->getUser());
                $subscriber = new UserSubscriber($entityManager, $badgeRepo);
                $dispatcher->addSubscriber($subscriber);
                
                $dispatcher->dispatch($event, UserTenCommentsEvent::NAME);
            }

            return $this->redirectToRoute("deal_details", ['id' => $id]);
        }

        return $this->render('deal/details.html.twig', [
            'deal' => $deal,
            'deal_rating' => $repo->getDealRating($deal),
            'comments' => $comments,
            'form' => $form->createView(),
            'isVoted' => $isVoted
        ]);
    }

    /**
     * @Route("/deals/{id}/vote-up", name="deal_vote_up", requirements={"id"="\d+"})
     */
    public function voteUp(int $id, RateRepository $rateRepo, DealRepository $dealRepo, BadgeRepository $badgeRepo)
    {
        $user = $this->getUser();
        $deal = $dealRepo->find($id);
        $rate = $rateRepo->findOneBy(array('user' => $user, 'deal' => $deal));
        if(empty($rate)) {
            $rate = new Rate();
            $rate->setUser($user);
            $rate->setDeal($deal);
        }
        $rate->setMark(1);
        $manager = $this->getDoctrine()->getManager();
        $manager->persist($rate);
        $manager->flush();

        self::onVotedEvent($badgeRepo, $manager);

        return $this->json(['rating' => $dealRepo->getDealRating($deal)]);
    }

    /**
     * @Route("/deals/{id}/vote-down", name="deal_vote_down", requirements={"id"="\d+"})
     */
    public function voteDown(int $id, RateRepository $rateRepo, DealRepository $dealRepo, BadgeRepository $badgeRepo) //TODO: remove code duplication
    {
        $user = $this->getUser();
        $deal = $dealRepo->find($id);
        $rate = $rateRepo->findOneBy(array('user' => $user, 'deal' => $deal));
        if(empty($rate)) {
            $rate = new Rate();
            $rate->setUser($user);
            $rate->setDeal($deal);
        }
        $rate->setMark(-1);
        $manager = $this->getDoctrine()->getManager();
        $manager->persist($rate);
        $manager->flush();

        self::onVotedEvent($badgeRepo, $manager);

        return $this->json(['rating' => $dealRepo->getDealRating($deal)]);
    }

    private function onVotedEvent(BadgeRepository $badgeRepo, EntityManagerInterface $manager){
        if(count($this->getUser()->getMarks()) == 10){
            $dispatcher = new EventDispatcher();
            $event = new UserTenMarksGivenEvent($this->getUser());
            $subscriber = new UserSubscriber($manager, $badgeRepo);
            $dispatcher->addSubscriber($subscriber);
            
            $dispatcher->dispatch($event, UserTenMarksGivenEvent::NAME);
        }
    }

    /**
     * @Route("/deals/{id}/save", name="deal_save", requirements={"id"="\d+"})
     */
    public function save(int $id, DealRepository $dealRepo, UserRepository $userRepo){
        $user = $this->getUser();
        $deal = $dealRepo->find($id);
        // $votedDeals = $user->getSavedDeal();
        $isFind = self::getSavedDeals($user, $id);

        // foreach ($votedDeals as $votedDeal) {
        //     if($votedDeal->getId() == $id){
        //         $isFind = true;
        //     }
        // }

        if($isFind){
            $user->removeSavedDeal($deal);
        }
        else {
            $user->addSavedDeal($deal);
        }

        $manager = $this->getDoctrine()->getManager();
        $manager->persist($user);
        $manager->flush();

        return $this->json(['saved' => $isFind]);
    }

    private function getSavedDeals(User $user, int $id){
        $isFind = false;
        $votedDeals = $user->getSavedDeal();
        foreach ($votedDeals as $votedDeal) {
            if($votedDeal->getId() == $id){
                $isFind = true;
            }
        }

        return $isFind;
    }

    /**
     * @Route("/deals/report/{id}", name="report_deal", requirements={"id"="\d+"})
     */
    public function reportOneDeal(int $id, DealRepository $dealRepo, UserRepository $userRepo, MailerInterface $mailer){
        $deal = $dealRepo->find($id);
        $admins = $userRepo->findAdmins();
        foreach ($admins as $admin) {
            // https://www.youtube.com/watch?v=cct_TBw1pRM
            // $sent = mail($email, 'One deal is report !', '<p>The deal "' .$deal->getTitle(). '" is report. See it <a href="http://localhost:8081/deal/' .$id. '">here</a></p>');
            // $sent = mail('alexisgoncalves@1811@gmail.com', 'One deal is report !', 'Deal is reportted !');
            $email = (new Email())
            ->from('hello@example.com')
            ->to($admin->getEmail())
            ->subject('One deal is reportted !')
            ->html('<p>The deal "' .$deal->getTitle(). '" is report. See it <a href="http://localhost:8081/deal/' .$id. '">here</a></p>');

            $mailer->send($email);
        }
        return $this->json(['reported' => true]);
    }

    /**
     * @Route("/deals/expired/{id}", name="expired_deal", requirements={"id"="\d+"})
     */

    public function setDealAsExpired(int $id, DealRepository $dealRepo){
        $deal = $dealRepo->find($id);
        $deal->setExpired(true);
        
        $manager = $this->getDoctrine()->getManager();
        $manager->persist($deal);
        $manager->flush();

        return $this->json(['expired' => true]);
    }
}
