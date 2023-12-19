<?php

namespace App\Domain\Apd\Entity;

use Exception;

class DuctNetwork
{
    public $id;
    private $name;
    private $projectId;

    private $air;
    private $altitude;
    private $temperature;

    private $generalMaterial;
    private $additionalApd;

    private array $ductSections;

    private $totalLinearApd;
    private $totalSingularApd;
    private $totalAdditionalApd;
    private $totalApd;

    public function __construct(string $name, string $generalMaterial, int $additionalApd = 0)
    {
        $this->air = new Air();
        $this->altitude = $this->air->getAltitude();
        $this->temperature = $this->air->getTemperature();
        $this->ductSections = [];

        $this->name = $name;
        $this->generalMaterial = $generalMaterial;
        $this->additionalApd = $additionalApd;
    }
    
    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getProjectId(): int
    {
        return $this->projectId;
    }

    public function getAir(): Air
    {
        return $this->air;
    }

    public function getAltitude(): int
    {
        return $this->altitude;
    }

    public function getTemperature(): float
    {
        return $this->temperature;
    }

    public function getGeneralMaterial(): string
    {
        return $this->generalMaterial;
    }

    public function getAdditionalApd(): int
    {
        return $this->additionalApd;
    }

    public function getDuctSections(): array
    {
        return $this->ductSections;
    }

    public function getTotalLinearApd(): float
    {
        return $this->totalLinearApd;
    }

    public function getTotalSingularApd(): float
    {
        return $this->totalSingularApd;
    }

    public function getTotalAdditionalApd(): float
    {
        return $this->totalAdditionalApd;
    }

    public function getTotalApd(): float
    {
        return $this->totalApd;
    }

    public function calculate()
    {
        $this->setTotalLinearApd();
        $this->setTotalSingularApd();
        $this->setTotalAdditionalApd();
        $this->setTotalApd();
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function setProjectId(int $projectId): static
    {
        $this->projectId = $projectId;

        return $this;
    }

    public function setAir(Air $air): static
    {
        $this->air = $air;

        return $this;
    }

    public function setAltitude(int $altitude): static
    {
        $this->altitude = $altitude;
        $this->air->setAltitude($altitude);

        foreach ($this->ductSections as $ductSection) {
            $ductSection->setAir($this->air);
            $ductSection->calculate();
        }

        $this->calculate();

        return $this;
    }

    public function setTemperature(float $temperature): static
    {
        $this->temperature = $temperature;
        $this->air->setTemperature($temperature);

        foreach ($this->ductSections as $ductSection) {
            $ductSection->setAir($this->air);
            $ductSection->calculate();
        }

        $this->calculate();

        return $this;
    }

    public function setGeneralMaterial(string $generalMaterial): static
    {
        $this->generalMaterial = $generalMaterial;

        return $this;
    }

    public function setAdditionalApd(int $additionalApd): static
    {
        $this->additionalApd = $additionalApd;

        return $this;
    }

    public function addDuctSection(DuctSection $ductSection): static
    {
        $this->ductSections[] = $ductSection;

        $this->calculate();

        return $this;
    }

    public function removeDuctSection(DuctSection $ductSectionToRemove): static
    {
        if (count($this->ductSections) === 0) {
            throw new Exception('There is no duct Section to remove.');
        }

        $this->ductSections = array_filter($this->ductSections, static function ($element) use($ductSectionToRemove){
            if ($element->getId() !== $ductSectionToRemove->getId()) {
                return $element;
            }
        });

        $this->calculate();

        return $this;
    }

    public function setTotalLinearApd(): static
    {
        $totalLinearApd = 0;

        foreach ($this->ductSections as $ductSection) {
            $totalLinearApd += $ductSection->getLinearApd();
        }

        $this->totalLinearApd = round($totalLinearApd, 3);

        return $this;
    }

    public function setTotalSingularApd(): static
    {
        $totalSingularApd = 0;

        foreach ($this->ductSections as $ductSection) {
            $totalSingularApd += $ductSection->getSingularApd();
        }

        $this->totalSingularApd = round($totalSingularApd, 3);

        return $this;
    }

    public function setTotalAdditionalApd(): static
    {
        $totalAdditionalApd = 0;

        foreach ($this->ductSections as $ductSection) {
            $totalAdditionalApd += $ductSection->getAdditionalApd();
        }

        $this->totalAdditionalApd = $totalAdditionalApd;

        return $this;
    }

    public function setTotalApd(): static
    {
        $this->totalApd = round($this->additionalApd
            + $this->totalLinearApd
            + $this->totalSingularApd
            + $this->totalAdditionalApd, 3);

        return $this;
    }
}