<?php

namespace App\Domain\Apd\Entity;

use Exception;

class Project
{
    public int $id;
    private string $name;
    private int $userId;

    private float $generalAltitude;
    private float $generalTemperature;

    private array $ductNetworks;

    public function __construct($name)
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

    public function setUserId($userId): static
    {
        $this->userId = $userId;

        return $this;
    }

    public function setGeneralAltitude($altitude): static
    {
        $this->generalAltitude = $altitude;

        foreach ($this->ductNetworks as $ductNetwork) {
            $ductNetwork->setAltitude($altitude);
        }

        return $this;
    }

    public function setGeneralTemperature($temperature): static
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