<?php

namespace App\Entity;

use App\Repository\DuctNetworkRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DuctNetworkRepository::class)]
class DuctNetwork
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?int $additionalApd = null;

    #[ORM\Column(nullable: true)]
    private ?float $temperature = null;

    #[ORM\Column(nullable: true)]
    private ?int $altitude = null;

    #[ORM\OneToMany(mappedBy: 'ductNetwork', targetEntity: ductSection::class, orphanRemoval: true)]
    private Collection $ductSections;

    #[ORM\Column(nullable: true)]
    private ?float $totalLinearApd = null;

    #[ORM\Column(nullable: true)]
    private ?float $totalSingularApd = null;

    #[ORM\Column(nullable: true)]
    private ?int $totalAdditionalApd = null;

    #[ORM\Column(nullable: true)]
    private ?int $totalAlladditionalApd = null;

    #[ORM\Column(nullable: true)]
    private ?float $totalApd = null;

    public function __construct()
    {
        $this->ductSections = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getTemperature(): ?float
    {
        return $this->temperature;
    }

    public function setTemperature(?float $temperature): static
    {
        $this->temperature = $temperature;

        return $this;
    }

    public function getAltitude(): ?int
    {
        return $this->altitude;
    }

    public function setAltitude(?int $altitude): static
    {
        $this->altitude = $altitude;

        return $this;
    }

    /**
     * @return Collection<int, ductSection>
     */
    public function getDuctSections(): Collection
    {
        return $this->ductSections;
    }

    public function addDuctSection(ductSection $ductSection): static
    {
        if (!$this->ductSections->contains($ductSection)) {
            $this->ductSections->add($ductSection);
            $ductSection->setDuctNetwork($this);
        }

        return $this;
    }

    public function removeDuctSection(ductSection $ductSection): static
    {
        if ($this->ductSections->removeElement($ductSection)) {
            // set the owning side to null (unless already changed)
            if ($ductSection->getDuctNetwork() === $this) {
                $ductSection->setDuctNetwork(null);
            }
        }

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

    public function getTotalAlladditionalApd(): ?int
    {
        return $this->totalAlladditionalApd;
    }

    public function setTotalAlladditionalApd(?int $totalAlladditionalApd): static
    {
        $this->totalAlladditionalApd = $totalAlladditionalApd;

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
}
