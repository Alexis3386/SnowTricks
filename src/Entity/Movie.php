<?php

namespace App\Entity;

use App\Repository\MovieRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MovieRepository::class)]
class Movie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column()]
    private string $code;

    #[ORM\ManyToOne(inversedBy: 'movies')]
    private ?Trick $trick = null;

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @param string $code
     * @return Movie
     */
    public function setCode(string $code): self
    {
        $this->code = $code;
        return $this;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getTrick(): ?Trick
    {
        return $this->trick;
    }

    public function setTrick(?Trick $trick): self
    {
        $this->trick = $trick;

        return $this;
    }
}
