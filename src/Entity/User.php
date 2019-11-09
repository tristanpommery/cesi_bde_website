<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Security\Core\User\UserInterface;


class User implements UserInterface
{
    private $id;

    private $fakeUser;

    private $firstName;

    private $lastName;

    private $genre;

    private $email;

    private $password;

    private $roles = [];

    private $image;

    private $campus;

    private $promotion;

    private $galleries;

    private $events;

    private $comments;

    private $associations;

    public function __construct()
    {
        $this->galleries = new ArrayCollection();
        $this->events = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->associations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getGenre(): ?string
    {
        return $this->genre;
    }

    public function setGenre(string $genre): self
    {
        $this->genre = $genre;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getRoles(): ?array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';

        return array_unique(roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

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

    
    public function getCampus(): ?Campus
    {
        return $this->campus;
    }
    
    public function setCampus(?Campus $campus): self
    {
        $this->campus = $campus;
        
        return $this;
    }
    
    public function getPromotion(): ?Promotion
    {
        return $this->promotion;
    }
    
    public function setPromotion(?Promotion $promotion): self
    {
        $this->promotion = $promotion;
        
        return $this;
    }
    
    public function getGalleries(): Collection
    {
        return $this->galleries;
    }
    
    public function addGallery(Gallery $gallery): self
    {
        if (!$this->galleries->contains($gallery)) {
            $this->galleries[] = $gallery;
            $gallery->addUser($this);
        }
        
        return $this;
    }
    
    public function removeGallery(Gallery $gallery): self
    {
        if ($this->galleries->contains($gallery)) {
            $this->galleries->removeElement($gallery);
            $gallery->removeUser($this);
        }
        
        return $this;
    }
    
    public function getEvents(): Collection
    {
        return $this->events;
    }
    
    public function addEvent(Event $event): self
    {
        if (!$this->events->contains($event)) {
            $this->events[] = $event;
            $event->addUser($this);
        }
        
        return $this;
    }
    
    public function removeEvent(Event $event): self
    {
        if ($this->events->contains($event)) {
            $this->events->removeElement($event);
            $event->removeUser($this);
        }
        
        return $this;
    }
    
    public function getComments(): Collection
    {
        return $this->comments;
    }
    
    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setUser($this);
        }
        
        return $this;
    }
    
    public function removeComment(Comment $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getUser() === $this) {
                $comment->setUser(null);
            }
        }
        
        return $this;
    }
    
    public function getAssociations(): Collection
    {
        return $this->associations;
    }
    
    public function addAssociation(Association $association): self
    {
        if (!$this->associations->contains($association)) {
            $this->associations[] = $association;
            $association->addUser($this);
        }
        
        return $this;
    }
    
    public function removeAssociation(Association $association): self
    {
        if ($this->associations->contains($association)) {
            $this->associations->removeElement($association);
            $association->removeUser($this);
        }
        
        return $this;
    }

    public function getUsername(): string
    {
        return (string) $this->email;
    }
    
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }
    
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }
}