<?php

namespace App\Tests\Domain\Apd\Entity;

use App\Domain\Apd\Entity\DuctNetwork;
use App\Domain\Apd\Entity\DuctSection;
use App\Domain\Apd\Factory\DuctSectionFactory;

class DuctSectionBuilder
{
    private $id = null;
    private $ductNetwork;
    private $name = 'duct section 1';
    private $ductNetworkId;
    private $air;
    private $shape = 'circular';
    private $material;
    private $flowrate = 5000;
    private $length = 1;
    private $singularities = [
        '90_elbow' => 1
    ];
    private $additionalApd = 10;
    private $diameter = 500;
    private $width = null;
    private $height = null;

    public function __construct()
    {
        $this->ductNetwork = new DuctNetwork('Duct network 1', 'galvanised_steel');
        $this->ductNetwork->setId(1);
        $this->ductNetworkId = $this->ductNetwork->getId();
        $this->air = $this->ductNetwork->getAir();
        $this->material = $this->ductNetwork->getGeneralMaterial();
    }

    public function build(): DuctSection
    {
        $id = $this->id ?? mt_rand(0, 500);

        $ductSectionFactory = new DuctSectionFactory();
        $ductSectionFactory->setSectionTechnicalDatas([
            "air" => $this->air,
            "shape" => $this->shape,
            "material" => $this->material,
            "flowrate" => $this->flowrate,
            "length" => $this->length,
            "singularities" => $this->singularities,
            "additionalApd" => $this->additionalApd,
            "diameter" => $this->diameter,
        ]);

        $ductSection = $ductSectionFactory->createDuctSection();

        $ductSection->setId($id)
            ->setName($this->name)
            ->setDuctNetworkId($this->ductNetworkId);

        return $ductSection;
    }

    public static function aDuctSection()
    {
        return new DuctSectionBuilder;
    }
    
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    public function setDuctNetworkId($ductNetworkId)
    {
        $this->ductNetworkId = $ductNetworkId;

        return $this;
    }

    public function setShape($shape)
    {
        $this->shape = $shape;

        return $this;
    }

    public function setMaterial($material)
    {
        $this->material = $material;

        return $this;
    }

    public function setFlowrate($flowrate)
    {
        $this->flowrate = $flowrate;

        return $this;
    }

    public function setLength($length)
    {
        $this->length = $length;

        return $this;
    }

    public function setSingularities($singularities)
    {
        $this->singularities = $singularities;

        return $this;
    }

    public function setAdditionalApd($additionalApd)
    {
        $this->additionalApd = $additionalApd;

        return $this;
    }

    public function setDiameter($diameter)
    {
        $this->diameter = $diameter;

        return $this;
    }

    public function setWidth($width)
    {
        $this->width = $width;

        return $this;
    }

    public function setHeight($height)
    {
        $this->height = $height;

        return $this;
    }

    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }
}