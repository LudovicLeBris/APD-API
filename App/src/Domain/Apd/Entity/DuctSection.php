<?php

namespace App\Domain\Apd\Entity;

use App\SharedKernel\Model\Material;
use App\SharedKernel\Model\Singularity;

class DuctSection
{
    protected int $id;
    protected string $name;
    protected int $ductNetworkId;
    
    protected Air $air;

    protected string $shape;
    protected string $material;
    protected int $flowrate;
    protected float $length;
    protected array $singularities;
    protected int $additionalApd;

    protected ?int $diameter = null;
    protected ?int $width = null;
    protected ?int $height = null;

    protected float $equivDiameter;
    protected float $ductSectionsSection;
    protected float $flowspeed;
    protected float $linearApd;
    protected float $singularApd;
    protected float $totalApd;
    
    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDuctNetworkId(): int
    {
        return $this->ductNetworkId;
    }

    public function getAir(): Air
    {
        return $this->air;
    }
    
    public function getShape()
    {
        return $this->shape;
    }
    
    public function getMaterial(): string
    {
        return $this->material;
    }
    
    public function getFlowrate(): int
    {
        return $this->flowrate;
    }

    public function getLength(): float
    {
        return $this->length;
    }

    public function getSingularities(): array
    {
        return $this->singularities;
    }

    public function getAdditionalApd(): int
    {
        return $this->additionalApd;
    }

    public function getDuctSectionsSection(): float
    {
        return $this->ductSectionsSection;
    }

    public function getFlowspeed(): float
    {
        return $this->flowspeed;
    }

    public function getLinearApd(): float
    {
        return $this->linearApd;
    }

    public function getSingularApd(): float
    {
        return $this->singularApd;
    }

    public function getTotalApd(): float
    {
        return $this->totalApd;
    }

    public function calculate()
    {
        $this->setEquivDiameter();
        $this->setDuctSectionsSection();
        $this->setFlowspeed();
        $this->setLinearApd();
        $this->setSingularApd();
        $this->setTotalApd();
    }

    public function setId($id): static
    {
        $this->id = $id;

        return $this;
    }

    public function setName($name): static
    {
        $this->name = $name;

        return $this;
    }
    
    public function setDuctNetworkId($ductNetworkId): static
    {
        $this->ductNetworkId = $ductNetworkId;

        return $this;
    }

    public function setAir(Air $air): static
    {
        $this->air = $air;

        return $this;
    }

    public function setEquivDiameter(): static 
    {
        return $this;
    }

    public function setDuctSectionsSection(): static
    {
        $this->ductSectionsSection = round((pi() * ($this->equivDiameter/1000)**2)/4, 3);

        return $this;
    }

    public function setFlowspeed(): static
    {
        $this->flowspeed = round(($this->flowrate / 3600) / $this->ductSectionsSection, 3);

        return $this;
    }

    public function setLinearApd(): static
    {
        $reynolds = ($this->flowspeed * ($this->equivDiameter * 10 ** -3)) / $this->air->getViscosity();
        $laminarLambda = 64 / $reynolds;

        if($reynolds <= 2400){
            $this->linearApd = ($laminarLambda * $this->length * $this->air->getDensity() *
            ($this->flowspeed ** 2)) / (2 * ($this->equivDiameter / 1000));
            return true;
        }

        $roughness = Material::getRoughness($this->material);
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

        $this->linearApd = round(($lambdaDef * $this->length * $this->air->getDensity() * ($this->flowspeed ** 2)) /
            (2 * ($this->equivDiameter / 1000)), 3);

        return $this;
    }

    public function setSingularApd(): static
    {
        $totalSingularities = 0;
        foreach($this->singularities as $singularityName => $singularityCount)
        {
            $totalSingularities += Singularity::getSingularity($this->shape, $singularityName) * $singularityCount;
        }
        $this->singularApd = round($totalSingularities * $this->air->getDensity() * ($this->flowspeed ** 2) / 2, 3);

        return $this;
    }

    public function setTotalApd(): static
    {
        $this->totalApd = round($this->linearApd + $this->singularApd + $this->additionalApd, 3);

        return $this;
    }

}