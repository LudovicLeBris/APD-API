<?php

namespace App\Domain\Apd\UseCase\AddDuctSection;

class AddDuctSectionRequest
{
    public $name;
    public $ductNetworkId;
    
    public $shape;
    public $flowrate;
    public $length;
    public $singularities;
    public $additionalApd = 0;

    public $diameter = null;
    public $width = null;
    public $height = null;

    public function __construct($ductNetworkId)
    {
        $this->ductNetworkId = $ductNetworkId;
    }

    public function setContent($requestContent)
    {
        foreach ($requestContent as $field => $value) {
            if (property_exists($this, $field)) {
                $this->$field = $value;
            }
        }

        return $this;
    }

    public function getContent(): array
    {
        return [
            "ductNetworkId" => $this->ductNetworkId,
            "name" => $this->name,
            "shape" => $this->shape,
            "flowrate" => $this->flowrate,
            "length" => $this->length,
            "singularities" => $this->singularities,
            "additionalApd" => $this->additionalApd,
            "diameter" => $this->diameter,
            "width" => $this->width,
            "height" => $this->height
        ];
    }
}