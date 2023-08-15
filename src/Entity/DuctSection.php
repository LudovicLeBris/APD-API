<?php

namespace App\Entity;

use App\Repository\DuctSectionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DuctSectionRepository::class)]
class DuctSection
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 32)]
    private ?string $shape = null;

    #[ORM\Column(length: 32)]
    private ?string $material = null;

    #[ORM\Column(nullable: true)]
    private ?int $diameter = null;

    #[ORM\Column(nullable: true)]
    private ?int $width = null;

    #[ORM\Column(nullable: true)]
    private ?int $height = null;

    #[ORM\Column]
    private ?int $flowrate = null;

    #[ORM\Column]
    private ?float $length = null;

    #[ORM\Column(type: Types::SIMPLE_ARRAY, nullable: true)]
    private ?array $singularities = null;

    #[ORM\Column(nullable: true)]
    private ?int $additionalApd = null;

    #[ORM\Column(nullable: true)]
    private ?float $equivDiameter = null;

    #[ORM\Column(nullable: true)]
    private ?float $section = null;

    #[ORM\Column(nullable: true)]
    private ?float $flowSpeed = null;

    #[ORM\Column(nullable: true)]
    private ?float $linearApd = null;

    #[ORM\Column(nullable: true)]
    private ?float $singularApd = null;

    #[ORM\Column(nullable: true)]
    private ?float $totalApd = null;

    #[ORM\ManyToOne(inversedBy: 'ductSections')]
    #[ORM\JoinColumn(nullable: false)]
    private ?DuctNetwork $ductNetwork = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getMaterial(): ?string
    {
        return $this->material;
    }

    public function setMaterial(string $material): static
    {
        $this->material = $material;

        return $this;
    }

    public function getDiameter(): ?int
    {
        return $this->diameter;
    }

    public function setDiameter(?int $diameter): static
    {
        $this->diameter = $diameter;

        return $this;
    }

    public function getWidth(): ?int
    {
        return $this->width;
    }

    public function setWidth(?int $width): static
    {
        $this->width = $width;

        return $this;
    }

    public function getHeight(): ?int
    {
        return $this->height;
    }

    public function setHeight(?int $height): static
    {
        $this->height = $height;

        return $this;
    }

    public function getFlowrate(): ?int
    {
        return $this->flowrate;
    }

    public function setFlowrate(int $flowrate): static
    {
        $this->flowrate = $flowrate;

        return $this;
    }

    public function getLength(): ?float
    {
        return $this->length;
    }

    public function setLength(float $length): static
    {
        $this->length = $length;

        return $this;
    }

    public function getSingularities(): ?array
    {
        return $this->singularities;
    }

    public function setSingularities(?array $singularities): static
    {
        $this->singularities = $singularities;

        return $this;
    }

    public function getAdditionalApd(): ?int
    {
        return $this->additionalApd;
    }

    public function setAdditionalApd(?int $additionalApd): static
    {
        $this->additionalApd = $additionalApd;

        return $this;
    }

    public function getEquivDiameter(): ?float
    {
        return $this->equivDiameter;
    }

    public function setEquivDiameter(): static
    {
        if ($this->shape === 'circular') {
            $this->equivDiameter = $this->diameter;
        } elseif ($this->shape === 'rectangular') {
            $this->equivDiameter = round((1.265 * ($this->width * $this->height) ** 0.6) 
                / ($this->width + $this->height) ** 0.2, 3);
        } else {
            $this->equivDiameter = null;
        }

        return $this;
    }

    public function getSection(): ?float
    {
        return $this->section;
    }

    public function setSection(): static
    {
        $this->section = round((pi() * ($this->equivDiameter/1000)**2)/4,3);
        
        return $this;
    }

    public function getFlowSpeed(): ?float
    {
        return $this->flowSpeed;
    }

    public function setFlowSpeed(): static
    {
        $this->flowSpeed = round(($this->flowrate / 3600) / $this->section,3);

        return $this;
    }

    public function getLinearApd(): ?float
    {
        return $this->linearApd;
    }

    public function setLinearApd(?float $linearApd): static
    {
        $this->linearApd = $linearApd;

        return $this;
    }

    public function getSingularApd(): ?float
    {
        return $this->singularApd;
    }

    public function setSingularApd(?float $singularApd): static
    {
        $this->singularApd = $singularApd;

        return $this;
    }

    public function getTotalApd(): ?float
    {
        return $this->totalApd;
    }

    public function setTotalApd(?float $totalApd): static
    {
        $this->totalApd = $totalApd;

        return $this;
    }

    public function getDuctNetwork(): ?DuctNetwork
    {
        return $this->ductNetwork;
    }

    public function setDuctNetwork(?DuctNetwork $ductNetwork): static
    {
        $this->ductNetwork = $ductNetwork;

        return $this;
    }
}
