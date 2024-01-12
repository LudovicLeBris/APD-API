<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\DuctNetworkRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity(repositoryClass: DuctNetworkRepository::class)]
class DuctNetwork
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private ?int $altitude = null;

    #[ORM\Column]
    private ?float $temperature = null;

    #[ORM\Column(length: 255)]
    private ?string $generalMaterial = null;

    #[ORM\Column]
    private ?int $additionalApd = null;

    #[ORM\Column(nullable: true)]
    private ?float $totalLinearApd = null;

    #[ORM\Column(nullable: true)]
    private ?float $totalSingularApd = null;

    #[ORM\Column(nullable: true)]
    private ?int $totalAdditionalApd = null;

    #[ORM\Column(nullable: true)]
    private ?float $totalApd = null;

    #[ORM\OneToMany(mappedBy: 'ductNetwork', targetEntity: DuctSection::class, orphanRemoval: true, cascade: ['PERSIST'])]
    private Collection $ductSections;

    #[ORM\Column(type: Types::OBJECT)]
    private ?object $air = null;

    #[ORM\ManyToOne(inversedBy: 'ductNetworks')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Project $project = null;

    public function __construct()
    {
        $this->ductSections = new ArrayCollection();
    }

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

    public function getAltitude(): ?int
    {
        return $this->altitude;
    }

    public function setAltitude(int $altitude): static
    {
        $this->altitude = $altitude;

        return $this;
    }

    public function getTemperature(): ?float
    {
        return $this->temperature;
    }

    public function setTemperature(float $temperature): static
    {
        $this->temperature = $temperature;

        return $this;
    }

    public function getGeneralMaterial(): ?string
    {
        return $this->generalMaterial;
    }

    public function setGeneralMaterial(string $generalMaterial): static
    {
        $this->generalMaterial = $generalMaterial;

        return $this;
    }

    public function getAdditionalApd(): ?int
    {
        return $this->additionalApd;
    }

    public function setAdditionalApd(int $additionalApd): static
    {
        $this->additionalApd = $additionalApd;

        return $this;
    }

    public function getTotalLinearApd(): ?float
    {
        return $this->totalLinearApd;
    }

    public function setTotalLinearApd(?float $totalLinearApd): static
    {
        $this->totalLinearApd = $totalLinearApd;

        return $this;
    }

    public function getTotalSingularApd(): ?float
    {
        return $this->totalSingularApd;
    }

    public function setTotalSingularApd(?float $totalSingularApd): static
    {
        $this->totalSingularApd = $totalSingularApd;

        return $this;
    }

    public function getTotalAdditionalApd(): ?int
    {
        return $this->totalAdditionalApd;
    }

    public function setTotalAdditionalApd(?int $totalAdditionalApd): static
    {
        $this->totalAdditionalApd = $totalAdditionalApd;

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

    /**
     * @return Collection<int, DuctSection>
     */
    public function getDuctSections(): Collection
    {
        return $this->ductSections;
    }

    public function addDuctSection(DuctSection $ductSection): static
    {
        if (!$this->ductSections->contains($ductSection)) {
            $this->ductSections->add($ductSection);
            $ductSection->setDuctNetwork($this);
        }

        return $this;
    }

    public function removeDuctSection(DuctSection $ductSection): static
    {
        if ($this->ductSections->removeElement($ductSection)) {
            // set the owning side to null (unless already changed)
            if ($ductSection->getDuctNetwork() === $this) {
                $ductSection->setDuctNetwork(null);
            }
        }

        return $this;
    }

    public function getAir(): ?object
    {
        return $this->air;
    }

    public function setAir(object $air): static
    {
        $this->air = $air;

        return $this;
    }

    public function getProject(): ?Project
    {
        return $this->project;
    }

    public function setProject(?Project $project): static
    {
        $this->project = $project;

        return $this;
    }
}
