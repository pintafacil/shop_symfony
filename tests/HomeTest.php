<?php

namespace App\Tests;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ExampleTest extends WebTestCase
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    public function testExample()
    {
        // Create a new example entity and persist it to the database
        $example = new Product();
        $example->setName('TestProduct');
        $this->entityManager->persist($example);
        $this->entityManager->flush();

        // Retrieve the example from the database and assert that the name is correct
        $example = $this->entityManager->getRepository(Example::class)->find($example->getId());

        $this->assertEquals('Test', $example->getName());
    }
}