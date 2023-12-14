<?php

namespace App\Tests\Domain\Apd\Entity;

use App\Domain\Apd\Entity\Air;
use App\Domain\Apd\Entity\DuctNetwork;
use App\Tests\Domain\Apd\Entity\DuctSectionBuilder;

class DuctNetworkBuilder
{
    private $id = null;
    private $name = 'duct network 1';
    private $projectId = 1;
    private $air;
    private $altitude;
    private $temperature;
    private $generalMaterial = 'galvanised_steel';
    private $additionalApd = 10;
    private $ductSections = [];

    public function __construct()
    {
        $this->air = new Air();
        $this->altitude = $this->air->getAltitude();
        $this->temperature = $this->air->getTemperature();
    }

    public function build(): DuctNetwork
    {
        $id = $this->id ?? mt_rand(0, 500);
        
        $ductNetwork = new DuctNetwork(
            $this->name,
            $this->generalMaterial,
            $this->additionalApd
        );
        $ductSection = DuctSectionBuilder::aDuctSection()->build();

        $ductNetwork
            ->setId($id)
            ->setProjectId($this->projectId)
            ->addDuctSection($ductSection);

        return $ductNetwork;
    }

    public static function aDuctNetwork()
    {
        return new DuctNetworkBuilder;
    }

    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    public function setProjectId($projectId)
    {
        $this->projectId = $projectId;

        return $this;
    }

    public function setAltitude($altitude)
    {
        $this->altitude = $altitude;

        return $this;
    }

    public function setTemperature($temperature)
    {
        $this->temperature = $temperature;

        return $this;
    }

    public function setGeneralMaterial($generalMaterial)
    {
        $this->generalMaterial = $generalMaterial;

        return $this;
    }

    public function setAdditionalApd($additionalApd)
    {
        $this->additionalApd = $additionalApd;

        return $this;
    }

    public function setDuctSections($ductSections)
    {
        $this->ductSections = $ductSections;

        return $this;
    }
}