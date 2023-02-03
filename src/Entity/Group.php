<?php

namespace App\Entity;

use App\Repository\GroupRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GroupRepository::class)]
#[ORM\Table(name: '`group`')]
class Group
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column()]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'group', targetEntity: Trick::class)]
    private Collection $Tricks;

    public function __construct()
    {
        $this->Tricks = new ArrayCollection();
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

    /**
     * @return Collection<int, Trick>
     */
    public function getTricks(): Collection
    {
        return $this->Tricks;
    }

    public function addTrick(Trick $trick): self
    {
        if (!$this->Tricks->contains($trick)) {
            $this->Tricks->add($trick);
            $trick->setGroup($this);
        }

        return $this;
    }

    public function removeTrick(Trick $trick): self
    {
        $this->Tricks->removeElement($trick);

        return $this;
    }
}
