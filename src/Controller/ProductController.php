<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\Price;
use App\Form\EditFormType;
use App\Form\ProductFormType;
use App\Repository\ProductRepository;
use App\Repository\PriceRepository;

use Doctrine\ORM\EntityManagerInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Filesystem\Filesystem;

class ProductController extends AbstractController
{

    private $em;

    public function __construct(ProductRepository $productRepository, PriceRepository $priceRepository, EntityManagerInterface $em)
    {  
        $this->productRepository = $productRepository;
        $this->priceRepository = $priceRepository;
        $this->em = $em;
    }

    //create product
    #[Route('/product/create', name: 'product.create')]
    public function create(Request $request): Response
    {
        $product = new Product();
        $productForm = $this->createForm(ProductFormType::class, $product);
        $productForm->handleRequest($request);

        if ($productForm->isSubmitted() && $productForm->isValid()) {
            
            $data = $productForm->getData();
            $dataPrice = $productForm->get('price')->getData();
            
            $price = new Price();
            
            $price->setValue($dataPrice);
            $price->setProductid($product);
            $price->setTimestamp(new \DateTime());
            $price->setStatus(true);

            $product->addPrice($price);
            $product->setStatus(true);
            $product->setName($data->getName());
            $product->setDescription($data->getDescription());

            $productImage = $productForm->get('imagepath')->getData();
            $imageName = md5(uniqid()).'.'.$productImage->guessExtension();

            if ($productImage) {
                try {
                    $productImage->move(
                        $this->getParameter('kernel.project_dir').'/public/uploads',
                        $imageName
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                    return new Response('Error: '.$e->getMessage());
                }

                $product->setImagepath('/uploads/'.$imageName);
            }
            $this->em->persist($price);

            $this->em->persist($product);
            $this->em->flush();

            //dd($product);
            //exit;
            
            return $this->redirectToRoute('home');
        }

        return $this->render('product/form.html.twig', [
            'productForm' => $productForm->createView(),
        ]);
    }


    //update product
    #[Route('/product/update/{id}', name: 'product.update')]
    public function update($id, Request $request): Response
    {
        $product = $this->productRepository->find($id);
        $price = $this->priceRepository->findOneBy(['Productid' => $id, 'status' => true]);
        $oldPrice = $price->getValue();

        //dd($oldPrice);
        //exit;

        //dd($price);
        //exit;

        $productForm = $this->createForm(EditFormType::class, $product);
        $productForm->handleRequest($request);

        if ($productForm->isSubmitted() && $productForm->isValid()) {
            
            $data = $productForm->getData();
            $dataPrice = $productForm->get('price')->getData();
            
            //dd($dataPrice);
            //exit;

            if (($oldPrice != $dataPrice) && ($dataPrice != null)){ //if price is changed
                $price->setStatus(false);
                //$product->setPriceStatus($price, false); //this is a test
                
                $newPrice = new Price();
                
                $newPrice->setValue($dataPrice);
                $newPrice->setProductid($product);
                $newPrice->setTimestamp(new \DateTime());
                $newPrice->setStatus(true);
                $product->addPrice($newPrice);

                $this->em->persist($newPrice);
                $this->em->persist($price);
                $this->em->flush();
            }  

            $product->setName($data->getName());
            $product->setDescription($data->getDescription());

            //$productImage = $productForm->get('imagepath')->getData();
            $newProductImage = $productForm->get('newimagepath')->getData();
            
            if ($newProductImage){ //if image is changed

                if (file_exists($this->getParameter('kernel.project_dir').'/public'.$product->getImagepath())) {
                    $this->getParameter('kernel.project_dir').'/public'.$product->getImagepath();
                    
                    $imageName = md5(uniqid()).'.'.$newProductImage->guessExtension();

                    try {
                        $newProductImage->move(
                            $this->getParameter('kernel.project_dir').'/public/uploads',
                            $imageName
                        );
                    } catch (FileException $e) {
                        // ... handle exception if something happens during file upload
                        return new Response('Error: '.$e->getMessage());
                    }

                    $fs = new Filesystem();
                    $fs->remove($this->getParameter('kernel.project_dir').'/public'.$product->getImagepath());

                    $product->setImagepath('/uploads/'.$imageName);
                    
                    
                    $this->em->persist($product);
                    $this->em->flush();
                }

                //return $this->redirectToRoute('home');
            }


            return $this->redirectToRoute('home');
            
        
    
        }

        return $this->render('product/form.html.twig', [
            'productForm' => $productForm->createView(),
        ]);

    }

    //disable product and its prices
    #[Route('/product/disable/{id}', name: 'product.disable')]
    public function disable($id): Response
    {   
        $product = $this->productRepository->find($id); 
        
        $product->setStatus(false);

        $this->em->persist($product);
        $this->em->flush();

        return $this->redirectToRoute('home');
    }

    //enable product and its prices
    #[Route('/product/enable/{id}', name: 'product.enable')]
    public function enable($id): Response
    {   
        $product = $this->productRepository->find($id); 
        
        $product->setStatus(true);

        $this->em->persist($product);
        $this->em->flush();

        return $this->redirectToRoute('home');
    }

    //HARD delete product and its prices
    #[Route('/product/delete/{id}', name: 'product.delete')]
    public function delete( $id): Response
    {   
        $product = $this->productRepository->find($id); 

        $fs = new Filesystem();
        $fs ->remove($this->getParameter('kernel.project_dir').
            '/public'.$product->getImagepath());
        $priceRepo = $this->priceRepository->findBy(['Productid' => $id]);
        
        //delete a repository of prices 
        foreach ($priceRepo as $price) {
            $this->em->remove($price);
        }

        $this->em->remove($product);
        $this->em->flush();

        return $this->redirectToRoute('home');
    }

  
    #[Route('/product/{id}', name: 'product.detail')]
    public function index(Product $product, $id): Response
    {
        $price = $this->priceRepository->findOneBy(['Productid' => $id, 'status' => true]);
        $priceValue = $price->getValue();
        //dd($priceValue);
        //exit;

        return $this->render('product/detail.html.twig', [
            'product' => $product, 
            'price' => $priceValue,
        ]);
    }

}