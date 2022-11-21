<?php

namespace App\Controller;

use App\Repository\DealRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class RestController extends AbstractController
{
    /**
     * @Route("/api/deals", name="api_deals")
     */
    public function index(DealRepository $dealRepo)
    {
        $deals = $dealRepo->getLastWeekDeals();

        return $this->json($deals);
    }
}
