<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
       $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager)
    {
        $user = new User();
        
        $user->setEmail('admin@admin.com');
        $user->setRoles(array('ROLE_ADMIN', 'ROLE_USER'));
        $password = $this->hasher->hashPassword($user, 'admin');
        $user->setPassword($password);

        $manager->persist($user);
        $manager->flush();

        // $product = new Product();
    }

}
