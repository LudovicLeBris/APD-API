<?php

namespace App\Entity;

use App\Entity\Air;
use App\Utils\Data;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\DuctSectionRepository;

#[ORM\Entity(repositoryClass: DuctSectionRepository::class)]
class DuctSection
{
    /**
     * Duct section identifier
     *
     * @var integer|null
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * Duct section shape (circular or rectangular)
     *
     * @var string|null
     */
    #[ORM\Column(length: 32)]
    private ?string $shape = null;

    /**
     * Duct section material
     *
     * @var string|null
     */
    #[ORM\Column(length: 32)]
    private ?string $material = null;

    /**
     * Duct section diameter (in millimeter mm, if shape is circular)
     *
     * @var integer|null
     */
    #[ORM\Column(nullable: true)]
    private ?int $diameter = null;

    /**
     * Duct section width (in millimeter mm, if shape is rectangular)
     *
     * @var integer|null
     */
    #[ORM\Column(nullable: true)]
    private ?int $width = null;

    /**
     * Duct section height (in millimeter mm, if shape is rectangular)
     *
     * @var integer|null
     */
    #[ORM\Column(nullable: true)]
    private ?int $height = null;

    /**
     * Duct section flow rate (in cubic meter per hour m3/h)
     *
     * @var integer|null
     */
    #[ORM\Column]
    private ?int $flowrate = null;

    /**
     * Duct section length (in meter m)
     *
     * @var float|null
     */
    #[ORM\Column]
    private ?float $length = null;

    /**
     * Duct section singularities (type and count)
     *
     * @var array|null
     */
    #[ORM\Column(type: Types::SIMPLE_ARRAY, nullable: true)]
    private ?array $singularities = null;

    /**
     * Duct section additional apd (in Pascal Pa)
     *
     * @var integer|null
     */
    #[ORM\Column(nullable: true)]
    private ?int $additionalApd = null;

    /**
     * Duct section equivalent diameter (in millimeter mm)
     *
     * @var float|null
     */
    #[ORM\Column(nullable: true)]
    private ?float $equivDiameter = null;

    /**
     * Duct section section (in square meter m²)
     *
     * @var float|null
     */
    #[ORM\Column(nullable: true)]
    private ?float $section = null;

    /**
     * Duct section flow speed (in meter per second m/s)
     *
     * @var float|null
     */
    #[ORM\Column(nullable: true)]
    private ?float $flowSpeed = null;

    /**
     * Duct section linear apd (in Pascal Pa)
     *
     * @var float|null
     */
    #[ORM\Column(nullable: true)]
    private ?float $linearApd = null;

    /**
     * Duct section singular apd (in Pascal Pa)
     *
     * @var float|null
     */
    #[ORM\Column(nullable: true)]
    private ?float $singularApd = null;

    /**
     * Duct section total apd (in Pascal Pa)
     *
     * @var float|null
     */
    #[ORM\Column(nullable: true)]
    private ?float $totalApd = null;

    /**
     * Duct section relation to his duct network
     *
     * @var DuctNetwork|null
     */
    #[ORM\ManyToOne(inversedBy: 'ductSections')]
    #[ORM\JoinColumn(nullable: false)]
    private ?DuctNetwork $ductNetwork = null;

    /**
     * Unique instance of the Air class
     *
     * @var Air
     */
    public Air $air;

    public function __construct(
        string $shape,
        string $material,
        ?int $diameter,
        ?int $width,
        ?int $height,
        int $flowrate,
        int $length,
        array $singularities,
        int $additionalApd = 0
    )
    {
        $this->air = Air::getInstance();

        $this->shape = $shape;
        $this->material = $material;
        $this->diameter = $diameter;
        $this->width = $width;
        $this->height = $height;
        $this->flowrate = $flowrate;
        $this->length = $length;
        $this->singularities = $singularities;
        $this->additionalApd = $additionalApd;

        $this->calculate();
    }

    /**
     * Get the duct section Id
     *
     * @return integer|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Get the duct section shape
     *
     * @return string|null
     */
    public function getShape(): ?string
    {
        return $this->shape;
    }

    /**
     * Set the duct section shape
     *
     * @param string $shape
     * @return static
     */
    public function setShape(string $shape): static
    {
        $this->shape = $shape;

        return $this;
    }

    /**
     * Get the duct section material
     *
     * @return string|null
     */
    public function getMaterial(): ?string
    {
        return $this->material;
    }

    /**
     * Set the duct section material
     *
     * @param string $material
     * @return static
     */
    public function setMaterial(string $material): static
    {
        $this->material = $material;

        return $this;
    }

    /**
     * Get the duct section diameter
     *
     * @return integer|null
     */
    public function getDiameter(): ?int
    {
        return $this->diameter;
    }

    /**
     * Set the duct section diameter
     *
     * @param integer|null $diameter
     * @return static
     */
    public function setDiameter(?int $diameter): static
    {
        $this->diameter = $diameter;

        return $this;
    }

    /**
     * Get the duct section width
     *
     * @return integer|null
     */
    public function getWidth(): ?int
    {
        return $this->width;
    }

    /**
     * Set the duct section width
     *
     * @param integer|null $width
     * @return static
     */
    public function setWidth(?int $width): static
    {
        $this->width = $width;

        return $this;
    }

    /**
     * Get the duct section height
     *
     * @return integer|null
     */
    public function getHeight(): ?int
    {
        return $this->height;
    }

    /**
     * Set the duct section height
     *
     * @param integer|null $height
     * @return static
     */
    public function setHeight(?int $height): static
    {
        $this->height = $height;

        return $this;
    }

    /**
     * Get the duct section flow rate
     *
     * @return integer|null
     */
    public function getFlowrate(): ?int
    {
        return $this->flowrate;
    }

    /**
     * Set the duct section flow rate
     *
     * @param integer $flowrate
     * @return static
     */
    public function setFlowrate(int $flowrate): static
    {
        $this->flowrate = $flowrate;

        return $this;
    }

    /**
     * Get the duct section length
     *
     * @return float|null
     */
    public function getLength(): ?float
    {
        return $this->length;
    }

    /**
     * Set the duct section length
     *
     * @param float $length
     * @return static
     */
    public function setLength(float $length): static
    {
        $this->length = $length;

        return $this;
    }

    /**
     * Get the duct section singularities
     *
     * @return array|null
     */
    public function getSingularities(): ?array
    {
        return $this->singularities;
    }

    /**
     * Set the duct section singularities
     *
     * @param array|null $singularities
     * @return static
     */
    public function setSingularities(?array $singularities): static
    {
        $this->singularities = $singularities;

        return $this;
    }

    /**
     * Get the duct section additional apd
     *
     * @return integer|null
     */
    public function getAdditionalApd(): ?int
    {
        return $this->additionalApd;
    }

    /**
     * Set the duct section additional apd
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
     * Get the duct section equivalent diameter
     *
     * @return float|null
     */
    public function getEquivDiameter(): ?float
    {
        return $this->equivDiameter;
    }

    /**
     * Set the duct section equivalent diameter
     *
     * @return static
     */
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

    /**
     * Get the duct section section
     *
     * @return float|null
     */
    public function getSection(): ?float
    {
        return $this->section;
    }

    /**
     * Set the duct section section
     *
     * @return static
     */
    public function setSection(): static
    {
        $this->section = round((pi() * ($this->equivDiameter/1000)**2)/4,3);
        
        return $this;
    }

    /**
     * Get the duct section flow speed
     *
     * @return float|null
     */
    public function getFlowSpeed(): ?float
    {
        return $this->flowSpeed;
    }

    /**
     * Set the duct section flow speed
     *
     * @return static
     */
    public function setFlowSpeed(): static
    {
        $this->flowSpeed = round(($this->flowrate / 3600) / $this->section,3);

        return $this;
    }

    /**
     * Get the duct section linear apd
     *
     * @return float|null
     */
    public function getLinearApd(): ?float
    {
        return $this->linearApd;
    }

    /**
     * Set the duct section linear apd
     *
     * @return static
     */
    public function setLinearApd(): static
    {
        $reynolds = ($this->flowSpeed * ($this->equivDiameter * 10 ** -3)) / $this->air->getViscosity();
        $laminarLambda = 64 / $reynolds;

        if($reynolds <= 2400){
            $this->linearApd = ($laminarLambda * $this->length * $this->air->getDensity() *
            ($this->flowSpeed ** 2)) / (2 * ($this->equivDiameter / 1000));
            return true;
        }

        $roughness = Data::getRoughness($this->material);
        $b = 2.51 / $reynolds;

        $roughnessLambda = (1 / (-2 * log10($roughness))) ** 2;
        $lambdaList = [(1 / (-2 * log10($roughness + $b * (1 / sqrt($roughnessLambda))))) ** 2];

        for ($i=0; $i < 12 ; $i++) { 
            $tempLambda = (1 / (-2 * log10($roughness + $b * (1 / sqrt(end($lambdaList)))))) ** 2;
            $lambdaList[] = $tempLambda;
        }
        $lambdaList2 = [];
        foreach($lambdaList as $lambda){
            $lambdaList2[] = $lambda - $roughnessLambda;
        }
        $minLambda2 = min(array_slice($lambdaList2, 2));
        $lambdaDef = $lambdaList[array_search($minLambda2, array_slice($lambdaList2, 0, 6))];

        $this->linearApd = round(($lambdaDef * $this->length * $this->air->getDensity() * ($this->flowSpeed ** 2)) /
            (2 * ($this->equivDiameter / 1000)), 3);

        return $this;
    }

    /**
     * Get the duct section singular apd
     *
     * @return float|null
     */
    public function getSingularApd(): ?float
    {
        return $this->singularApd;
    }

    /**
     * Set the duct section singular apd
     *
     * @return static
     */
    public function setSingularApd(): static
    {
        $totalSingularities = 0;
        foreach($this->singularities as $singularityName => $singularityCount)
        {
            $totalSingularities += Data::getSingularity($this->shape, $singularityName) * $singularityCount;
        }
        $this->singularApd = round($totalSingularities * $this->air->getDensity() * ($this->flowSpeed ** 2) / 2, 3);

        return $this;
    }

    /**
     * Get the duct section total apd
     *
     * @return float|null
     */
    public function getTotalApd(): ?float
    {
        return $this->totalApd;
    }

    /**
     * Set the duct section total apd
     *
     * @return static
     */
    public function setTotalApd(): static
    {
        $this->totalApd = $this->linearApd + $this->singularApd + $this->additionalApd;

        return $this;
    }

    /**
     * Get the duct section relation to his duct network
     *
     * @return DuctNetwork|null
     */
    public function getDuctNetwork(): ?DuctNetwork
    {
        return $this->ductNetwork;
    }

    /**
     * Set the duct section relation to his duct network
     *
     * @param DuctNetwork|null $ductNetwork
     * @return static
     */
    public function setDuctNetwork(?DuctNetwork $ductNetwork): static
    {
        $this->ductNetwork = $ductNetwork;

        return $this;
    }

    /**
     * Set the air instance altitude
     *
     * @param int $altitude
     * @return static
     */
    public function setAirAltitude(int $altitude): static
    {
        $this->air->setAltitude($altitude);
        $this->calculate();

        return $this;
    }

    /**
     * Set the air instance temperature
     *
     * @param int $temperature
     * @return static
     */
    public function setAirTemperature(int $temperature): static
    {
        $this->air->setTemperature($temperature);
        $this->calculate();

        return $this;
    }

    /**
     * Calculate all datas for duct section
     *
     * @return static
     */
    public function calculate(): static
    {
        $this->setEquivDiameter();
        $this->setSection();
        $this->setFlowSpeed();
        $this->setLinearApd();
        $this->setSingularApd();
        $this->setTotalApd();

        return $this;
    }
}
