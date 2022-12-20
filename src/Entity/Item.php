<?php

namespace App\Entity;

use App\Repository\ItemRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ItemRepository::class)]
class Item
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $quantity = null;

    #[ORM\ManyToOne(inversedBy: 'items')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Product $Productid = null;

    #[ORM\ManyToOne(inversedBy: 'items')]
    private ?Cart $Cartid = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getProductid(): ?Product
    {
        return $this->Productid;
    }

    public function setProductid(?Product $Productid): self
    {
        $this->Productid = $Productid;

        return $this;
    }

    public function getCartid(): ?Cart
    {
        return $this->Cartid;
    }

    public function setCartid(?Cart $Cartid): self
    {
        $this->Cartid = $Cartid;

        return $this;
    }
}
