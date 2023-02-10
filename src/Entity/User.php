<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column()]
    private string $name;

    #[ORM\Column()]
    private string $email;

    #[ORM\Column()]
    private string $password = '';

    #[ORM\Column(nullable: true)]
    private ?string $pathPhoto = null;

    #[ORM\OneToMany(targetEntity: Comment::class, mappedBy: 'user', orphanRemoval: true)]
    private Collection $userComment;

    #[ORM\Column()]
    private bool $isValidate = false;

    #[ORM\Column(nullable: true)]
    private ?string $token = null;

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

    public function getPassword(): string
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

    public function setPathPhoto(?string $pathPhoto): self
    {
        $this->pathPhoto = $pathPhoto;

        return $this;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(?string $token): self
    {
        $this->token = $token;

        return $this;
    }

    public function validate(): self
    {
        $this->isValidate = true;

        return $this;
    }

    public function isValidated() : bool
    {
        return $this->isValidate;
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
