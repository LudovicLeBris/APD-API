<?php

namespace App\Domain\Apd\Entity;

use Exception;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema:"ductNetwork",
    title:"duct network"
)]
class DuctNetwork
{
    #[OA\Property(
        title:"id",
        description:"Duct network's id",
        type:"integer",
        example:42
    )]
    public $id;

    #[OA\Property(
        title:"name",
        description:"Duct network's name",
        type:"string",
        example:"duct network n°1"
    )]
    private $name;

    #[OA\Property(
        title:"project id",
        description:"Duct network's associated project id",
        type:"integer",
        example:21
    )]
    private $projectId;

    #[OA\Property(
        title:"air",
        description:"Air instance of the duct network",
        type:"object",
        properties: [
            new OA\Property(property:"viscosity", title:"viscosity", description:"Viscosity property of the air", type:"number", format:"float", example:1.5080510051843115e-5),
            new OA\Property(property:"density", title:"density", description:"Density property of the air", type:"number", format:"float", example:1.2058928673556562),
            new OA\Property(property:"temperature", title:"temperature", description:"Temperature property of the air", type:"number", format:"float", example:18.2),
            new OA\Property(property:"altitude", title:"altitude", description:"Altitude property of the air", type:"integer", example:800),
        ]
    )]
    private $air;

    #[OA\Property(
        title:"altitude",
        description:"Duct network's altitude below sea level - in meter (m)",
        type:"integer",
        minimum:0,
        example:800
    )]
    private $altitude;

    #[OA\Property(
        title:"temperature",
        description:"Duct network's ambiant temperature - in degrees Celsius (°C)",
        type:"number",
        format:"float",
        example:18.2
    )]
    private $temperature;

    #[OA\Property(
        title:"general material",
        description:"Duct network's material, all duct sections are dependent of this property",
        type:"string",
        example:"galvanised_steel"
    )]
    private $generalMaterial;

    #[OA\Property(
        title:"additional apd",
        description:"Optional additional air pressure drop value who represent an accessory of duct network - in pascal (Pa)",
        type:"integer",
        minimum:1,
        example:50
    )]
    private $additionalApd;

    #[OA\Property(
        title:"duct sections",
        description:"All duct sections associated of this duct network",
        type:"array",
        items:
            new OA\Items(ref:"#/components/schemas/ductSection")
    )]
    private array $ductSections;

    #[OA\Property(
        title:"total linear apd",
        description:"Result of all linear apd calculation in this duct network - in pascal (Pa)",
        type:"number",
        format:"float",
        example:36.387
    )]
    private $totalLinearApd;

    #[OA\Property(
        title:"total singular apd",
        description:"Result of all singular apd calculation in this duct network - in pascal (Pa)",
        type:"number",
        format:"float",
        example:83.473
    )]
    private $totalSingularApd;

    #[OA\Property(
        title:"total additional apd",
        description:"Result of all additional apd calculation in this duct network - in pascal (Pa)",
        type:"integer",
        example:150
    )]
    private $totalAdditionalApd;

    #[OA\Property(
        title:"total apd",
        description:"Result of total of all apd calculation in this duct network - in pascal (Pa)",
        type:"number",
        format:"float",
        example:269.86
    )]
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