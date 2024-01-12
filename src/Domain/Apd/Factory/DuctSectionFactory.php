<?php

namespace App\Domain\Apd\Factory;

use Exception;
use App\Domain\Apd\Entity\DuctSection;
use App\Domain\Apd\Model\CircularDuctSection;
use App\Domain\Apd\Model\RectangularDuctSection;

class DuctSectionFactory
{
    private string $shape;
    private $datas;

    public function setSectionTechnicalDatas(array $datas): void
    {
        $this->datas = [];
        
        if (!array_key_exists('air', $datas)) {
            throw new Exception('Air is required');
        }
        
        if (!array_key_exists('shape', $datas)) {
            throw new Exception('Shape is required (circular or rectangular)');
        }

        $this->shape = $datas["shape"];
        
        if ($this->shape === 'circular' && !array_key_exists('diameter', $datas)) {
            throw new Exception('Datas must contain a diameter');
        }

        if ($this->shape === 'rectangular' && (!array_key_exists('width', $datas) || !array_key_exists('height', $datas))) {
            throw new Exception('Datas must contain a width & a height');
        }
        
        $this->datas = $datas;
    }
    
    public function createDuctSection(): DuctSection
    {
        return match($this->shape) {
            'circular' => new CircularDuctSection($this->datas),
            'rectangular' => new RectangularDuctSection($this->datas),
            default => throw new Exception('Invalid shape type')
        };
    }
}
