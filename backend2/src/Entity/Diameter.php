<?php

namespace App\Entity;

use App\Repository\DiameterRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DiameterRepository::class)]
class Diameter
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $diameter = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDiameter(): ?int
    {
        return $this->diameter;
    }

    public function setDiameter(int $diameter): static
    {
        $this->diameter = $diameter;

        return $this;
    }
}
