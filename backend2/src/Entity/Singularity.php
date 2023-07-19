<?php

namespace App\Entity;

use App\Repository\SingularityRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SingularityRepository::class)]
class Singularity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $shape = null;

    #[ORM\Column]
    private ?float $singularity = null;

    #[ORM\Column(length: 255)]
    private ?string $longName = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getShape(): ?string
    {
        return $this->shape;
    }

    public function setShape(string $shape): static
    {
        $this->shape = $shape;

        return $this;
    }

    public function getSingularity(): ?float
    {
        return $this->singularity;
    }

    public function setSingularity(float $singularity): static
    {
        $this->singularity = $singularity;

        return $this;
    }

    public function getLongName(): ?string
    {
        return $this->longName;
    }

    public function setLongName(string $longName): static
    {
        $this->longName = $longName;

        return $this;
    }
}
