<?php

namespace App\Entity;

use App\Repository\DuctNetworkRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DuctNetworkRepository::class)]
class DuctNetwork
{
    /**
     * Duct network identifier
     *
     * @var integer|null
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * Duct network additional apd (in Pascal Pa)
     *
     * @var integer|null
     */
    #[ORM\Column(nullable: true)]
    private ?int $additionalApd = null;

    /**
     * Duct network temperature (in celsius degree °C)
     *
     * @var int|null
     */
    #[ORM\Column(nullable: true)]
    private ?int $temperature = null;

    /**
     * Duct network altitude (in meter bellow sea m)
     *
     * @var integer|null
     */
    #[ORM\Column(nullable: true)]
    private ?int $altitude = null;

    /**
     * Duct network duct sections
     *
     * @var Collection
     */
    #[ORM\OneToMany(mappedBy: 'ductNetwork', targetEntity: ductSection::class, orphanRemoval: true)]
    private Collection $ductSections;

    /**
     * Duct network total linear apd (in Pascal Pa)
     *
     * @var float|null
     */
    #[ORM\Column(nullable: true)]
    private ?float $totalLinearApd = null;

    /**
     * Duct network toal singular apd (in Pascal Pa)
     *
     * @var float|null
     */
    #[ORM\Column(nullable: true)]
    private ?float $totalSingularApd = null;

    /**
     * Duct network total additional apd (in Pascal Pa)
     *
     * @var integer|null
     */
    #[ORM\Column(nullable: true)]
    private ?int $totalAdditionalApd = null;

    /**
     * Duct network total of all additonal apd (in Pascal Pa)
     *
     * @var integer|null
     */
    #[ORM\Column(nullable: true)]
    private ?int $totalAlladditionalApd = null;

    /**
     * Duct network total apd (in Pascal Pa)
     *
     * @var float|null
     */
    #[ORM\Column(nullable: true)]
    private ?float $totalApd = null;

    public function __construct($additionalApd = 0, $temperature = 20, $altitude = 0)
    {
        $this->ductSections = new ArrayCollection();

        $this->additionalApd = $additionalApd;
        $this->temperature = $temperature;
        $this->altitude = $altitude;
    }

    /**
     * Get the duct network indentifier
     *
     * @return integer|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Get the duct network additional apd
     *
     * @return integer|null
     */
    public function getAdditionalApd(): ?int
    {
        return $this->additionalApd;
    }

    /**
     * Set the duct network additional apd
     *
     * @param integer|null $additionalApd
     * @return static
     */
    public function setAdditionalApd(?int $additionalApd): static
    {
        $this->additionalApd = $additionalApd;

        return $this;
    }

    /**
     * Get the duct network temperature
     *
     * @return float|null
     */
    public function getTemperature(): ?float
    {
        return $this->temperature;
    }

    /**
     * Set the duct network temperature
     *
     * @param int|null $temperature
     * @return static
     */
    public function setTemperature(?int $temperature): static
    {
        $this->temperature = $temperature;

        foreach ($this->ductSections as $ductSection) {
            $ductSection->setAirTemperature($this->temperature);
        }

        $this->calculate();

        return $this;
    }

    /**
     * Get the duct network altitude
     *
     * @return integer|null
     */
    public function getAltitude(): ?int
    {
        return $this->altitude;
    }

    /**
     * Set the duct network altitude
     *
     * @param integer|null $altitude
     * @return static
     */
    public function setAltitude(?int $altitude): static
    {
        $this->altitude = $altitude;

        foreach ($this->ductSections as $ductSection) {
            $ductSection->setAirAltitude($this->altitude);
        }

        $this->calculate();

        return $this;
    }

    /**
     * Get the duct network duct sections
     * 
     * @return Collection<int, ductSection>
     */
    public function getDuctSections(): Collection
    {
        return $this->ductSections;
    }

    /**
     * Add a duct network duct section
     *
     * @param ductSection $ductSection
     * @return static
     */
    public function addDuctSection(ductSection $ductSection): static
    {
        if (!$this->ductSections->contains($ductSection)) {
            $ductSection->setAirTemperature($this->temperature);
            $ductSection->setAirAltitude($this->altitude);
            $ductSection->calculate();
            $this->ductSections->add($ductSection);
            $ductSection->setDuctNetwork($this);
        }

        $this->calculate();

        return $this;
    }

    /**
     * Remove a duct network duct section
     *
     * @param ductSection $ductSection
     * @return static
     */
    public function removeDuctSection(ductSection $ductSection): static
    {
        if ($this->ductSections->removeElement($ductSection)) {
            // set the owning side to null (unless already changed)
            if ($ductSection->getDuctNetwork() === $this) {
                $ductSection->setDuctNetwork(null);
            }
        }

        $this->calculate();

        return $this;
    }

    /**
     * Get the duct network total linear apd
     *
     * @return float|null
     */
    public function getTotalLinearApd(): ?float
    {
        return $this->totalLinearApd;
    }

    /**
     * Set the duct network total linear apd
     *
     * @return static
     */
    public function setTotalLinearApd(): static
    {
        $totalLinearApd = 0;
        
        foreach ($this->ductSections as $ductSection) {
            $totalLinearApd += $ductSection->getLinearApd();
        }

        $this->totalLinearApd = $totalLinearApd;

        return $this;
    }

    /**
     * Get the duct network total singular apd
     *
     * @return float|null
     */
    public function getTotalSingularApd(): ?float
    {
        return $this->totalSingularApd;
    }

    /**
     * Set the duct network total singular apd
     *
     * @return static
     */
    public function setTotalSingularApd(): static
    {
        $totalSingularApd = 0;

        foreach ($this->ductSections as $ductSection) {
            $totalSingularApd += $ductSection->getSingularApd();
        }
        
        $this->totalSingularApd = $totalSingularApd;

        return $this;
    }

    /**
     * Get the duct network total additional apd
     *
     * @return integer|null
     */
    public function getTotalAdditionalApd(): ?int
    {
        return $this->totalAdditionalApd;
    }

    
    /**
     * Set the duct network total additional apd
     *
     * @return static
     */
    public function setTotalAdditionalApd(): static
    {
        $totalAdditionalApd = 0;

        foreach ($this->ductSections as $ductSection) {
            $totalAdditionalApd += $ductSection->getAdditionalApd();
        }
        
        $this->totalAdditionalApd = $totalAdditionalApd;

        return $this;
    }

    /**
     * Get the duct network total all additional apd
     *
     * @return integer|null
     */
    public function getTotalAlladditionalApd(): ?int
    {
        return $this->totalAlladditionalApd;
    }

    /**
     * Set the duct network total all additional apd
     *
     * @return static
     */
    public function setTotalAlladditionalApd(): static
    {
        $this->totalAlladditionalApd = $this->totalAdditionalApd + $this->additionalApd;

        return $this;
    }

    /**
     * Get the duct network total apd
     *
     * @return float|null
     */
    public function getTotalApd(): ?float
    {
        return $this->totalApd;
    }

    /**
     * Set the duct network total apd
     *
     * @return static
     */
    public function setTotalApd(): static
    {
        $this->totalApd = $this->totalLinearApd + $this->totalSingularApd + $this->totalAlladditionalApd;

        return $this;
    }

    public function calculate()
    {
        $this->setTotalLinearApd();
        $this->setTotalSingularApd();
        $this->setTotalAdditionalApd();
        $this->setTotalAlladditionalApd();
        $this->setTotalApd();
    }
}
