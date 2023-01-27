<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    private ?string $pathPhoto = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Comment::class, orphanRemoval: true)]
    private Collection $userComment;

    public function __construct()
    {
        $this->userComment = new ArrayCollection();
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

    public function getPathPhoto(): ?string
    {
        return $this->pathPhoto;
    }

    public function setPathPhoto(string $pathPhoto): self
    {
        $this->pathPhoto = $pathPhoto;

        return $this;
    }

    /**
     * @return Collection<int, Comment>
     */
    public function getUserComment(): Collection
    {
        return $this->userComment;
    }

    public function addUserComment(Comment $userComment): self
    {
        if (!$this->userComment->contains($userComment)) {
            $this->userComment->add($userComment);
            $userComment->setUser($this);
        }

        return $this;
    }

    public function removeUserComment(Comment $userComment): self
    {
        if ($this->userComment->removeElement($userComment)) {
            // set the owning side to null (unless already changed)
            if ($userComment->getUser() === $this) {
                $userComment->setUser(null);
            }
        }

        return $this;
    }
}
