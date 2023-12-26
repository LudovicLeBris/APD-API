<?php

namespace App\Domain\Apd\Entity;

use Exception;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema:"project",
    title:"project"
)]
class Project
{
    #[OA\Property(
        title:"id",
        description:"Project's id",
        type:"integer",
        example:21
    )]
    public $id;
    
    #[OA\Property(
        title:"name",
        description:"Project's name",
        type:"string",
        example:"project A"
    )]
    private $name;

    #[OA\Property(
        title:"user id",
        description:"Project's associated user id",
        type:"integer",
        example:10
    )]
    private $userId;
    
    #[OA\Property(
        title:"general altitude",
        description:"Project's altitude below sea level, all duct networks and duct sections are dependant of this property - in meter (m)",
        type:"integer",
        minimum:0,
        example:800
    )]
    private $generalAltitude;

    #[OA\Property(
        title:"general temperature",
        description:"Project's temperature, all duct networks and duct sections are dependant of this property - in degrees Celsius (°C)",
        type:"number",
        format:"float",
        example:18.2
    )]
    private $generalTemperature;

    #[OA\Property(
        title:"duct networks",
        description:"All duct networks associated of this project",
        type:"array",
        items:
            new OA\Items(ref:"#/components/schemas/ductNetwork")
    )]
    private $ductNetworks;

    public function __construct(string $name)
    {
        $this->ductNetworks = [];
        $this->name = $name;
        $this->generalTemperature = 20.0;
        $this->generalAltitude = 0;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getuserId(): int
    {
        return $this->userId;
    }

    public function getGeneralAltitude(): int
    {
        return $this->generalAltitude;
    }

    public function getGeneralTemperature(): float
    {
        return $this->generalTemperature;
    }

    public function getDuctNetworks(): array
    {
        return $this->ductNetworks;
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

    public function setUserId(int $userId): static
    {
        $this->userId = $userId;

        return $this;
    }

    public function setGeneralAltitude(int $altitude): static
    {
        $this->generalAltitude = $altitude;

        foreach ($this->ductNetworks as $ductNetwork) {
            $ductNetwork->setAltitude($altitude);
        }

        return $this;
    }

    public function setGeneralTemperature(float $temperature): static
    {
        $this->generalTemperature = $temperature;

        foreach ($this->ductNetworks as $ductNetwork) {
            $ductNetwork->setTemperature($temperature);
        }

        return $this;
    }

    public function addDuctNetwork(DuctNetwork $ductNetwork): static
    {
        $this->ductNetworks[] = $ductNetwork;

        return $this;
    }

    public function removeDuctNetwork(DuctNetwork $ductNetworkToRemove): static
    {
        if (count($this->ductNetworks) === 0) {
            throw new Exception('There is no duct Network to remove.');
        }

        $this->ductNetworks = array_filter($this->ductNetworks, static function ($element) use($ductNetworkToRemove){
            if ($element->getId() !== $ductNetworkToRemove->getId()) {
                return $element;
            }
        });

        return $this;
    }
}