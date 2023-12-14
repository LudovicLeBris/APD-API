<?php

namespace App\Domain\Apd\Model;

use App\Domain\Apd\Entity\DuctSection;
use App\Domain\Apd\Factory\DuctSectionInterface;

class CircularDuctSection extends DuctSection implements DuctSectionInterface
{
    protected string $shape = 'circular';
    protected ?int $diameter;

    public function __construct($datas)
    {
        $this->air = $datas['air'];
        $this->material = $datas['material'];
        $this->flowrate = $datas['flowrate'];
        $this->length = $datas['length'];
        $this->diameter = $datas['diameter'];
        $this->singularities = $datas['singularities'];
        $this->additionalApd = $datas['additionalApd'];

        $this->calculate();
    }
    
    public function setEquivDiameter(): static
    {
        $this->equivDiameter = $this->diameter;

        return $this;
    }
}