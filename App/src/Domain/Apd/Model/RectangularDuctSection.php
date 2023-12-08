<?php

namespace App\Domain\Apd\Model;

use App\Domain\Apd\Entity\DuctSection;
use App\Domain\Apd\Factory\DuctSectionInterface;

class RectangularDuctSection extends DuctSection implements DuctSectionInterface
{
    protected string $shape = 'rectangular';
    protected ?int $width;
    protected ?int $height;

    public function __construct($datas)
    {
        $this->air = $datas['air'];
        $this->material = $datas['material'];
        $this->flowrate = $datas['flowrate'];
        $this->length = $datas['length'];
        $this->width = $datas['width'];
        $this->height = $datas['height'];
        $this->singularities = $datas['singularities'];
        $this->additionalApd = $datas['additionalApd'];

        $this->calculate();
    }
    
    public function setEquivDiameter(): static
    {
        $this->equivDiameter = (1.265 * ($this->width * $this->height) ** 0.6) 
        / ($this->width + $this->height) ** 0.2;

        return $this;
    }

    public function getWidth(): int
    {
        return $this->width;
    }

    public function getHeight(): int
    {
        return $this->height;
    }
}