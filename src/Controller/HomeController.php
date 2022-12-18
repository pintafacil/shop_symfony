<?php

namespace App\Controller;

use App\Repository\PriceRepository;
use App\Repository\ProductRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{

    #[Route('/', name: 'home')]
    public function index(ProductRepository $productRepository, PriceRepository $priceRepository): Response
    {   
        

        $prices = $priceRepository->findAll();
        $products = $productRepository->findAll();

        //dd($prices[0]->getProductid()->getId());
        //exit;

        return $this->render('home/index.html.twig', [  
            'products' => $products, 
            'prices' => $prices,
        ]);
    }
}
