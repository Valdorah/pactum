<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\UserRepository;
use App\Repository\DealRepository;
use App\Repository\CommentRepository;
use App\Repository\RateRepository;
use App\Form\AlertType;
use App\Form\UserType;
use App\Entity\Alert;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends AbstractController
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @Route("/user", name="user")
     */
    public function index(Request $request)
    {
        $user = $this->getUser();

        dump($user);
        
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $user = $form->getData();
            // $alert->setUser($user);

            $password = $user->getPassword();
            $user->setPassword($this->passwordEncoder->encodePassword(
                $user,
                $password
            ));

            // ... perform some action, such as saving the task to the database
            // for example, if Task is a Doctrine entity, save it!
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute("user");
        }

        return $this->render('user/general.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
        ]);
    }

    /**
     * @Route("/user/saved-deals", name="saved_deals")
     */
    public function userSavedDeals(){
        $deals = $this->getUser()->getSavedDeal();
        return $this->render('user/saved-deals.html.twig', [
            'deals' => $deals,
        ]);
    }

    /**
     * @Route("/user/posted-deals", name="posted_deals")
     */
    public function userPostedDeals(){
        $deals = $this->getUser()->getPostedDeals();
        return $this->render('user/posted-deals.html.twig', [
            'deals' => $deals,
        ]);
    }

    /**
     * @Route("/user/delete-account/{id}", name="delete-account", requirements={"id"="\d+"})
     */
    public function deleteAccount(int $id, UserRepository $userRepo){
        $user = $userRepo->find($id);

        $user->setUsername(NULL);
        $user->setPassword(NULL);
        $user->setEmail(NULL);
        $user->setAvatar('');

        $manager = $this->getDoctrine()->getManager();
        $manager->persist($user);
        $manager->flush();

        return $this->json(true);
    }

    /**
     * @Route("/user/overview", name="user_overview")
     */
    public function userOverview(DealRepository $dealRepo, CommentRepository $commentRepo, RateRepository $rateRepo){
        $user = $this->getUser();
        $nbPostedDeals = $dealRepo->countPostedDeals($user->getId());
        $nbComments = $commentRepo->nbComments($user->getId());
        $maxMark = $rateRepo->getMaxOfDealMark($user->getId());
        $avg = $rateRepo->getAvgOfDealMark($user->getId());
        $nbDeals = $dealRepo->countPostedDeals($user->getId());
        $nbHotDeals = $dealRepo->countPostedHotDeals($user->getId());
        $pourcentageHotDeals = ($nbHotDeals[0][1]/$nbDeals[0][1]) * 100;
        
        // dump($nbPostedDeals);
        // dump($nbComments);
        // dump($maxMark[0]);
        dump($avg);
        // dump($nbHotDeals);
        // dump($nbDeals);
        // dump($pourcentageHotDeals);

        return $this->render('user/overview.html.twig', [
            'nbPostedDeals' => $nbPostedDeals[0][1],
            'nbComments' => $nbComments[0][1],
            'maxMark' => empty($maxMark) ? 0 : $maxMark[0]['nb'],
            'pourcentageHotDeals' => $pourcentageHotDeals
        ]);
    }

    /**
     * @Route("/user/badges", name="user_badges")
     */
    public function userBadges(){
        $badges = $this->getUser()->getBadges();
        $badgeComment = null;
        $badgeMarks = null;
        $badgePosted = null;

        foreach ($badges as $badge) {
            if($badge->getTitle() == 'Training report'){
                $badgeComment = $badge;
            }
            if($badge->getTitle() == 'Watchers'){
                $badgeMarks = $badge;
            }
            if($badge->getTitle() == 'Guinea pig'){
                $badgePosted = $badge;
            }
        }

        $nbComments = count($this->getUser()->getComments());
        $nbDeals = count($this->getUser()->getPostedDeals());
        $nbMarks = count($this->getUser()->getMarks());

        return $this->render('user/badges.html.twig', [
            'badges' => $badges,
            'nbComments' => $nbComments,
            'nbDeals' => $nbDeals,
            'nbMarks' => $nbMarks,
            'badgeComment' => $badgeComment,
            'badgeMarks' => $badgeMarks,
            'badgePosted' => $badgePosted
        ]);
    }

    /**
     * @Route("/user/alerts", name="user_alerts")
     */
    public function userAlerts(Request $request, DealRepository $dealRepo){
        $user = $this->getUser();
        $userAlerts = $user->getAlerts();
        $deals = [];

        foreach ($userAlerts as $alert) {
            $d = $dealRepo->getDealContains($alert->getkeyWord(), $alert->getMinTemperature());
            $deals[$alert->getkeyWord()] = $d;
        }

        $alert = new Alert();
        $form = $this->createForm(AlertType::class, $alert);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $alert = $form->getData();
            $alert->setUser($user);

            // ... perform some action, such as saving the task to the database
            // for example, if Task is a Doctrine entity, save it!
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($alert);
            $entityManager->flush();

            return $this->redirectToRoute("user_alerts");
        }

        return $this->render('user/alerts.html.twig', [
            'form' => $form->createView(),
            'alerts' => $userAlerts,
            'deals' => $deals
        ]);
    }
}
