<?php

namespace App\Controller;

use App\Repository\PriceRepository;
use App\Repository\ProductRepository;
use App\Repository\StockRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{

    #[Route('/', name: 'home')]
    public function index(ProductRepository $productRepository, PriceRepository $priceRepository, StockRepository $stockRepository, UserRepository $userRepository): Response
    {   
        $prices = $priceRepository->findAll();
        $products = $productRepository->findAll();
        $stocks = $stockRepository->findAll();

        //dd($prices[0]->getProductid()->getId());

        return $this->render('home/index.html.twig', [  
            'products' => $products, 
            'prices' => $prices,
            'stocks' => $stocks,
        ]);
    }

    #[Route('/wtf', name: 'wtf')]
    public function wtf(): Response
    {   

        return $this->render('product/graphs.html.twig');
    }

    #[Route('/test_login', name: 'test_login')]
    public function test_login(): Response
    {   

        return $this->render('test/test.html.twig');
    }
}