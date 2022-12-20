<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Entity\Item;
use App\Entity\Product;
use Symfony\Bundle\SecurityBundle\Security;
use App\Entity\User;
use App\Repository\CartRepository;
use App\Repository\ItemRepository;
use App\Repository\ProductRepository;
use App\Repository\PriceRepository;
use App\Repository\StockRepository;
use App\Repository\UserRepository;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
   
    private $security;
    
    public function __construct(UserRepository $userRepository, PriceRepository $priceRepository, ProductRepository $productRepository, ItemRepository $itemRepository, CartRepository $cartRepository, EntityManagerInterface $em, Security $security)
    {   
        $this->userRepository = $userRepository;
        $this->priceRepository = $priceRepository;
        $this->productRepository = $productRepository;
        $this->itemRepository = $itemRepository;
        $this->cartRepository = $cartRepository;
        $this->security = $security;
        $this->em = $em;
    }

    #[Route('/cart', name: 'app_cart')]
    public function index(): Response
    {
        return $this->render('cart/index.html.twig', [
            
        ]);
    }

    //add to cart
    #[Route('/add_to_cart/{id}', name: 'add_to_cart')]
    public function add_to_cart(UserInterface $user, $id): Response
    {   
        
        $product = $this->productRepository->find($id);

        //find logged in user -> UserInterface $user
        /** @var User $user */
        $logged_user_cart = $user->getCartid();
        // dd($user);
        // exit;
        //create new item
        

        //find active price of product
        $price = $this->priceRepository->findOneBy(['Productid' => $product, 'status' => true])->getValue(); 
  

       
        //get Id of cart that has this product
        // $itemAgain = $this->itemRepository->findOneBy(['Productid' => $product]);
        // if($itemAgain){
        //     foreach ($itemAgain->getCarts() as $itemAgain2) {
        //         $itemAgainId = $itemAgain2->getId();
        //     }
        // }
        //   //if user added to cart this product before, dont allow them to add it again
        //   if($itemAgain){ //if user added to cart this product before
        //     if($logged_user_cart){ //if user has a cart (not null)
        //         $logged_user_cart_id= $logged_user_cart->getId();
        //             if($itemAgainId == $logged_user_cart_id){

        //                 //$this->addFlash('error', 'You already added this product to cart!'); //not working as expected
        //                 return $this->redirectToRoute('home');
        //             }
        //     }
        // }

        // if($logged_user_cart != null)
            $itemAgain = $this->itemRepository->findOneBy(['Productid' => $product, 'Cartid' => $logged_user_cart]);
        if($itemAgain){
            //$this->addFlash('error', 'You already added this product to cart!'); //not working as expected
            return $this->redirectToRoute('home');
        }

        $item = new Item();
        $item->setProductid($product);
        $item->setQuantity(1);
        //only create new cart if user doesnt have one
        if($user->getCartid() == null){
            $cart = new Cart();
            $cart->setTimestamp(new \DateTime());
            $cart->setTotalprice($price);
            $cart->addItem($item);
        }
        else{ //if user has a cart, add item to cart
            $cartId = $user->getCartid();
            $cart = $this->cartRepository->findOneBy(['id' => $cartId]);
            $cart->setTotalprice($cart->getTotalprice() + $price);
            $cart->addItem($item);
        }

        //add cart to user
        $user->setCartid($cart);

        //save to db
        $this->em->persist($item);
        $this->em->persist($cart);
        $this->em->flush();

        //dd($item);
        // dd($cart);
        // exit;

        return $this->redirectToRoute('home');
    }

  
}
