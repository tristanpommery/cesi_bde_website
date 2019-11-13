<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AssociationRepository")
 */
class Association
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="text")
     */
    private $Description;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $image;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Product", mappedBy="association")
     */
    private $products;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\FakeUser", mappedBy="associations")
     */
    private $fakeUsers;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Event", mappedBy="association")
     */
    private $event;


    public function __construct()
    {
        $this->products = new ArrayCollection();
        $this->fakeUsers = new ArrayCollection();
        $this->event = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->Description;
    }

    public function setDescription(string $Description): self
    {
        $this->Description = $Description;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return Collection|Product[]
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Product $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products[] = $product;
            $product->setAssociation($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        if ($this->products->contains($product)) {
            $this->products->removeElement($product);
            // set the owning side to null (unless already changed)
            if ($product->getAssociation() === $this) {
                $product->setAssociation(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|FakeUser[]
     */
    public function getFakeUsers(): Collection
    {
        return $this->fakeUsers;
    }

    public function addFakeUser(FakeUser $fakeUser): self
    {
        if (!$this->fakeUsers->contains($fakeUser)) {
            $this->fakeUsers[] = $fakeUser;
            $fakeUser->addAssociation($this);
        }

        return $this;
    }

    public function removeFakeUser(FakeUser $fakeUser): self
    {
        if ($this->fakeUsers->contains($fakeUser)) {
            $this->fakeUsers->removeElement($fakeUser);
            $fakeUser->removeAssociation($this);
        }

        return $this;
    }

    /**
     * @return Collection|Event[]
     */
    public function getEvent(): Collection
    {
        return $this->event;
    }

    public function addEvent(Event $event): self
    {
        if (!$this->event->contains($event)) {
            $this->event[] = $event;
            $event->setAssociation($this);
        }

        return $this;
    }

    public function removeEvent(Event $event): self
    {
        if ($this->event->contains($event)) {
            $this->event->removeElement($event);
            // set the owning side to null (unless already changed)
            if ($event->getAssociation() === $this) {
                $event->setAssociation(null);
            }
        }

        return $this;
    }
}
