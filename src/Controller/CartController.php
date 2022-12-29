<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Entity\Item;
use App\Entity\Product;
use App\Entity\User;
use App\Entity\Receipt;
use App\Entity\Stock;

use App\Repository\CartRepository;
use App\Repository\ItemRepository;
use App\Repository\ProductRepository;
use App\Repository\PriceRepository;
use App\Repository\StockRepository;
use App\Repository\UserRepository;
use App\Repository\ReceiptRepository;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    
    public function __construct(UserRepository $userRepository, PriceRepository $priceRepository, ProductRepository $productRepository, ItemRepository $itemRepository, CartRepository $cartRepository, ReceiptRepository $receiptRepository, StockRepository $stockRepository,EntityManagerInterface $em)
    {   
        $this->userRepository = $userRepository;
        $this->priceRepository = $priceRepository;
        $this->productRepository = $productRepository;
        $this->itemRepository = $itemRepository;
        $this->cartRepository = $cartRepository;
        $this->receiptRepository = $receiptRepository;
        $this->stockRepository = $stockRepository;
        $this->em = $em;
    }

    #[Route('/cart', name: 'app_cart')]
    public function index(UserInterface $user): Response
    {
        //find logged in user -> UserInterface $user
        /** @var User $user */
        $logged_user_cart = $user->getCartid();
        $items = $this->itemRepository->findBy(['Cartid' => $logged_user_cart]); //array of items in cart
        $itemTotal = count($items);

        $cart = $this->cartRepository->findOneBy(['id' => $logged_user_cart]);
        if($cart){
            $cartPrice = $cart->getTotalprice();
        }
        else{
            $cartPrice = 0;
            $cartTotalPrice = 0;
        }
        $i=0;

        foreach($items as $item){
            
            $itemId[$i] = $item->getProductid()->getId(); 
          
            $product = $this->productRepository->findBy(['id' => $itemId[$i]]);
            $itemPrice = $this->priceRepository->findOneBy(['Productid' => $itemId[$i], 'status' => true])->getValue();
            
            foreach($product as $product1){ //get cart collection of products
                $products[] = $product1;
            }
           
            $itemPrices[$i] = $itemPrice;

            $i++;
        }
 
        $shippingFee = 2.50;
        
        $cartTotalPrice = $cartPrice + $shippingFee;
        
        //get quantity of each item in cart
        $itemQuantities = $this->itemRepository->findBy(['Cartid' => $logged_user_cart]);
        foreach($itemQuantities as $itemQuantity1){
            $itemQuantity[] = $itemQuantity1->getQuantity();
        }

        // dd($itemQuantity);
        // exit;

        //products = array of collection of products in cart
        //itemId = array of product ids in cart
        //itemPrices = array of product prices in cart
        //itemQuantity = array of product quantities in cart
        //cartPrice = total price of cart
        //itemTotal = total number of items in cart

        if($itemTotal > 0){
            return $this->render('cart/index.html.twig', [
                'items' => $items,
                'cartPrice' => $cartPrice,
                'products' => $products,
                'itemPrices' => $itemPrices,
                'itemTotal' => $itemTotal,
                'shippingFee' => $shippingFee,
                'cartTotalPrice' => $cartTotalPrice,
                'itemQuantity' => $itemQuantity,
            ]);
        }
        else{
            return $this->render('cart/index_noitems.html.twig', [
            ]);
        }
        
    }

    //add to cart
    #[Route('/add_to_cart/{id}', name: 'add_to_cart')]
    public function add_to_cart(UserInterface $user, $id): Response
    {   
        //find product
        $product = $this->productRepository->find($id);

        //check if stock is enough
        $stock = $this->stockRepository->findOneBy(['Productid' => $product, 'status' => true]);
        if( 1 > $stock->getValue()){
            //flash message
            $this->addFlash('error', 'Not enough stock!');
            return $this->redirectToRoute('product.detail', ['id' => $id]);
        }
        //find logged in user -> UserInterface $user
        /** @var User $user */
        $logged_user_cart = $user->getCartid();

        //find active price of product
        $price = $this->priceRepository->findOneBy(['Productid' => $product, 'status' => true])->getValue(); 

        // if($logged_user_cart != null)
            $itemAgain = $this->itemRepository->findOneBy(['Productid' => $product, 'Cartid' => $logged_user_cart]);
        if($itemAgain){
            $this->addFlash('error', 'You already added this product to cart!'); 
            return $this->redirectToRoute('home');
        }

        //create new item
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

        $item->setCartid($cart);
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

    //remove from cart
    #[Route('/remove_from_cart/{id}', name: 'remove_from_cart')]
    public function remove_from_cart(UserInterface $user, $id): Response
    {   
        
        $product = $this->productRepository->find($id);

        //find logged in user -> UserInterface $user
        /** @var User $user */
        $logged_user_cart = $user->getCartid();

        //find active price of product
        $price = $this->priceRepository->findOneBy(['Productid' => $product, 'status' => true])->getValue(); 

        //find item
        $item = $this->itemRepository->findOneBy(['Productid' => $product, 'Cartid' => $logged_user_cart]);

        //find cart
        $cartId = $user->getCartid();
        $cart = $this->cartRepository->findOneBy(['id' => $cartId]);

        $quantity = $item->getQuantity();

        //remove item from cart
        $cart->removeItem($item);
        $cart->setTotalprice($cart->getTotalprice() - ($price*$quantity));
        

        //save to db
        $this->em->remove($item);
        $this->em->persist($cart);
        $this->em->flush();

        return $this->redirectToRoute('app_cart');
    }

    //update quantity
    #[Route('/update_quantity/{id}', name: 'update_quantity')]
    public function update_quantity(UserInterface $user, $id, Request $request): Response
    {   
        $message = null;
        

        $data = $request->request->get('itemQuantity');
        $dataInt = (int)$data;
        // dd($dataInt);
        // exit;
        $product = $this->productRepository->find($id);
        
        //find logged in user -> UserInterface $user
        /** @var User $user */
        $logged_user_cart = $user->getCartid();

        //find active price of product
        $price = $this->priceRepository->findOneBy(['Productid' => $product, 'status' => true])->getValue(); 

        //find item
        $item = $this->itemRepository->findOneBy(['Productid' => $product, 'Cartid' => $logged_user_cart]);

        //find cart
        $cartId = $user->getCartid();
        $cart = $this->cartRepository->findOneBy(['id' => $cartId]);
        $prevQuantity= $item->getQuantity();
        //update quantity
        $item->setQuantity($dataInt);
        $cart->setTotalprice($cart->getTotalprice() - ($price * $prevQuantity) + ($price * $dataInt));

        //check if stock is enough
        $stock = $this->stockRepository->findOneBy(['Productid' => $product , 'status' => true]);
        if($dataInt > $stock->getValue()){
            //flash message
            $this->addFlash('error', 'Not enough stock!');
            return $this->redirectToRoute('app_cart' , ['message' => $message]);
        }

        //save to db
        $this->em->persist($item);
        $this->em->persist($cart);
        $this->em->flush();

        return $this->redirectToRoute('app_cart');
    }

    //checkout
    #[Route('/checkout', name: 'cart.receipt')]
    public function checkout(UserInterface $user): Response
    {   
        //find logged in user -> UserInterface $user
        /** @var User $user */
        // dd($user);
        // exit;

        $logged_user_cart = $user->getCartid();
        
        //find cart
        $cartId = $user->getCartid();
        $cart = $this->cartRepository->findOneBy(['id' => $cartId]);

        //dd($cart);
        //find items
        $items = $cart->getItems();

        //find products
        $i = 0;
        foreach($items as $item){
            $itemId[$i] = $item->getProductid()->getId(); 
            $product = $this->productRepository->findBy(['id' => $itemId[$i]]);
            foreach($product as $product1){ //get cart collection of products
                $products[] = $product1;
            }
            $i++;
        }

        //find prices
        $i = 0;
        foreach($items as $item){
            $itemId[$i] = $item->getProductid()->getId(); 
            $itemPrice = $this->priceRepository->findOneBy(['Productid' => $itemId[$i], 'status' => true])->getValue();
            $itemPrices[$i] = $itemPrice;
            $i++;
        }

        //get quantity of each item
        $i = 0;
        foreach($items as $item){
            $itemQuantity[$i] = $item->getQuantity();
            $i++;
        }

        //get total price of each item*quantity
        $i = 0;
        foreach($items as $item){
            $itemXQtd[$i]=$itemQuantity[$i]*$itemPrices[$i];
            $i++;
        }

        $receipt = new Receipt();
        $receipt->setTimestamp(new \DateTime());
        $receipt->setUserid($user);
        $receipt->setTotalprice($logged_user_cart->getTotalprice());
        //maybe add more properties

        //update stock
        $i = 0;
        foreach($items as $item){
            $itemId[$i] = $item->getProductid()->getId();
            $productCheck[$i] = $this->productRepository->findOneBy(['id' => $itemId[$i]]);
            $stock = $this->stockRepository->findOneBy(['Productid' => $itemId[$i], 'status' => true]);
            $stock->setStatus(false);
            // dd($itemId[$i]);
            // exit;
            $newStock = new Stock();
            $newStock->setValue($stock->getValue() - $itemQuantity[$i]);
            $newStock->setProductid($productCheck[$i]);
            $newStock->setStatus(true);
            $newStock->setTimestamp(new \DateTime());
            
            $this->em->persist($stock);
            $this->em->persist($newStock);
            $this->em->flush();
            $i++;
        }

        //find a way to store receipt items

        //update items of cart
        foreach($items as $item){
            $this->em->remove($item);
            $this->em->flush();
        }

        //once receipt is created, delete cart
        $user->setCartid(null);
        $this->em->remove($cart);
        $this->em->flush();

        
        $cartPrice = $cart->getTotalprice();

        $receipt->setTotalprice($cartPrice + 2.5); // + shipping fee + tax - discount

        $this->em->persist($receipt);
        $this->em->flush();

        return $this->render('cart/receipt.html.twig', [
            'receipt' => $receipt,
            'user' => $user,
            'products' => $products,
            'cartPrice' => $cartPrice,
            'itemPrices' => $itemPrices,
            'itemQuantity' => $itemQuantity,
            'itemXQtd' => $itemXQtd,
        ]);
    }

    //show all receipts
    #[Route('/receipts', name: 'receipt.allreceipts')]
    public function receipts(UserInterface $user): Response
    {   
        //find logged in user -> UserInterface $user
        /** @var User $user */
        $logged_user_id = $user->getId();

        //find all receipts of logged in user
        $receipts = $this->receiptRepository->findBy(['Userid' => $logged_user_id]);
        foreach($receipts as $receipt){
            dd($receipt->getUserid()->getCartid());
            exit;
        }
        
        // dd($receipts);
        

        return $this->render('receipt/allreceipts.html.twig', [
            'receipts' => $receipts,
            'user' => $user,
        ]);
    }
}
