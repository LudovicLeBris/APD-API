<?php

namespace App\Domain\Apd\Entity;

use OpenApi\Attributes as OA;
use App\SharedKernel\Model\Material;
use App\SharedKernel\Model\Singularity;

#[OA\Schema(
    schema:"ductSection",
    title:"duct section"
)]
class DuctSection
{
    #[OA\Property(
        title:"id",
        description:"duct section id",
        type:"integer",
        example:84
    )]
    public $id;

    #[OA\Property(
        title:"name",
        description:"Duct network's name",
        type:"string",
        example:"section DE"
    )]
    protected $name;

    #[OA\Property(
        title:"duct network id",
        description:"Duct section's associated duct network id",
        type:"integer",
        example:42
    )]
    protected $ductNetworkId;

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

    protected $air;

    #[OA\Property(
        title:"shape",
        description:"Duct desction's shape - circular or rectangular",
        type:"string",
        pattern:"^circular|rectangular$",
        example:"circular"
    )]
    protected $shape;

    #[OA\Property(
        title:"material",
        description:"Duct desction's material",
        type:"string",
        example:"galvanised_steel"
    )]
    protected $material;

    #[OA\Property(
        title:"flow rate",
        description:"Duct section's flow rate _ in cubic meter per hour (m³/h)",
        type:"integer",
        minimum:100,
        example:2500
    )]
    protected $flowrate;

    #[OA\Property(
        title:"length",
        description:"Duct section's linear length - in meter (m)",
        type:"number",
        format:"float",
        minimum:0.1,
        example:5.4
    )]
    protected $length;

    #[OA\Property(
        title:"singularities",
        description:"List and number of singularities in the duct section",
        type:"array",
        items:
            new OA\Items(
                type:"object",
                properties: [
                    new OA\Property(property:"singularity", type:"string", title:"singularity type", example:"90_elbow"),
                    new OA\Property(property:"number", type:"integer", title:"number of singularity", example:2)
                    ],
            ),
        example: ['90_elbow' => 2]
    )]
    protected $singularities;

    #[OA\Property(
        title:"additional apd",
        description:"Optional additional air pressure drop value who represent an accessory of duct section - in pascal (Pa)",
        type:"integer",
        minimum:1,
        example:50
    )]
    protected $additionalApd;

    #[OA\Property(
        title:"diameter",
        description:"Duct section's diameter when shape is circular (normalized diameter) - in millimeter (mm)",
        type:"integer",
        example:315
    )]
    protected $diameter = null;

    #[OA\Property(
        title:"width",
        description:"Duct section's width when shape is rectangular - in millimeter (mm)",
        type:"integer",
        example:400
    )]
    protected $width = null;

    #[OA\Property(
        title:"height",
        description:"Duct section's height when shape is rectangular - in millimeter (mm)",
        type:"integer",
        example:300
    )]
    protected $height = null;

    #[OA\Property(
        title:"equiv diameter",
        description:"Duct section equivalent diameter calculation - in millimeter (mm)",
        type:"number",
        format:"float",
        example:380.697
    )]
    protected $equivDiameter;

    #[OA\Property(
        title:"duct section's section",
        description:"Section of the duct section calculation - in square meter (m²)",
        type:"number",
        format:"float",
        example:0.114
    )]
    protected $ductSectionsSection;

    #[OA\Property(
        title:"flow speed",
        description:"Duct section's flow speed calculation - in meter per second (m/s)",
        type:"number",
        format:"float",
        example:7.31
    )]
    protected $flowspeed;

    #[OA\Property(
        title:"linear apd",
        description:"Duct section's linear apd calculation - in pascal (Pa)",
        type:"number",
        format:"float",
        example:11.011
    )]
    protected $linearApd;

    #[OA\Property(
        title:"singular apd",
        description:"Duct section's singular apd calculation - in pascal (Pa)",
        type:"number",
        format:"float",
        example:9.693
    )]
    protected $singularApd;

    #[OA\Property(
        title:"total apd",
        description:"Duct section's total apd calculation - in pascal (Pa)",
        type:"number",
        format:"float",
        example:70.704
    )]
    protected $totalApd;
    
    public function getId(): ?int
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
    
    public function getShape(): string
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

    public function getDiameter(): ?int
    {
        return $this->diameter;
    }

    public function getWidth(): ?int
    {
        return $this->width;
    }

    public function getHeight(): ?int
    {
        return $this->height;
    }

    public function getEquivDiameter(): float
    {
        return $this->equivDiameter;
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
    
    public function setDuctNetworkId(int $ductNetworkId): static
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