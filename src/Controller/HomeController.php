<?php

namespace App\Controller;

use App\Entity\Deal;
use App\Entity\Comment;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(Request $request)
    {
        $hot = $request->query->get('hot');
        
        if(!isset($hot)){
            $deals = $this->getDoctrine()
                ->getRepository(Deal::class)
                ->getDealsOnLastWeek();
        }
        else{
            $deals = $this->getDoctrine()
                ->getRepository(Deal::class)
                ->getHotDeals();
        }

        $todayHotDeals = $this->getDoctrine()
                ->getRepository(Deal::class)
                ->getHotDealsToday();

        return $this->render('home/index.html.twig', [
            'deals' => $deals,
            'todayHotDeals' => $todayHotDeals
        ]);
    }
}
