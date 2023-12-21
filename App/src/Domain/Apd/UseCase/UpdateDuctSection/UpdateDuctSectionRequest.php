<?php

namespace App\Domain\Apd\UseCase\UpdateDuctSection;

class UpdateDuctSectionRequest
{
    public $id;
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

    public $material;

    public function __construct($ductNetworkId, $ductSectionId)
    {
        $this->ductNetworkId = $ductNetworkId;
        $this->id = $ductSectionId;
    }
    
    public function setContent($requestContent)
    {
        if ($requestContent) {
            foreach ($requestContent as $field => $value) {
                if (property_exists($this, $field)) {
                    $this->$field = $value;
                }
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
            "material" => $this->material,
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