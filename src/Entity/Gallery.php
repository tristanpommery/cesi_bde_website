<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\GalleryRepository")
 */
class Gallery
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
    private $image;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Event", inversedBy="galleries")
     * @ORM\JoinColumn(nullable=false)
     */
    private $event;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\FakeUser", inversedBy="galleries")
     * @ORM\JoinColumn(nullable=false)
     */
    private $fakeUser;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getEvent(): ?Event
    {
        return $this->event;
    }

    public function setEvent(?Event $event): self
    {
        $this->event = $event;

        return $this;
    }

    public function getFakeUser(): ?FakeUser
    {
        return $this->fakeUser;
    }

    public function setFakeUser(?FakeUser $fakeUser): self
    {
        $this->fakeUser = $fakeUser;

        return $this;
    }
}
