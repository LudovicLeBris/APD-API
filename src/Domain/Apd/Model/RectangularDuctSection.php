<?php

namespace App\Domain\Apd\Model;

use App\Domain\Apd\Entity\DuctSection;
use App\Domain\Apd\Factory\DuctSectionInterface;

class RectangularDuctSection extends DuctSection implements DuctSectionInterface
{
    public function __construct($datas)
    {
        $this->shape = 'rectangular';
        $this->air = $datas['air'];
        $this->material = $datas['material'];
        $this->flowrate = $datas['flowrate'];
        $this->length = $datas['length'];
        $this->width = $datas['width'];
        $this->height = $datas['height'];
        $this->singularities = $datas['singularities'];
        $this->additionalApd = $datas['additionalApd'];
        $this->diameter = null;

        $this->calculate();
    }
    
    public function setEquivDiameter(): static
    {
        $this->equivDiameter = (1.265 * ($this->width * $this->height) ** 0.6) 
        / ($this->width + $this->height) ** 0.2;

        return $this;
    }
}