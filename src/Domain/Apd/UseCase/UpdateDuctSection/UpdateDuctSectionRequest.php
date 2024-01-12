<?php

namespace App\Domain\Apd\UseCase\UpdateDuctSection;

use OpenApi\Attributes as OA;

#[OA\RequestBody(
    request:"updateDuctSection",
    required:"true",
    content: new OA\JsonContent(
        properties:[
            new OA\Property(
                property:"name",
                title:"name",
                description:"Name of the duct network",
                type:"string",
                example:"duct network n°1",
                nullable:"true"
            ),
            new OA\Property(
                property:"material",
                oneOf:[new OA\Schema(ref:"#/components/schemas/materialsName")],
                title:"material",
                description:"Duct desction's material",
                type:"string",
                example:"galvanised_steel",
                nullable:"true"
            ),
            new OA\Property(
                property:"shape",
                title:"shape",
                description:"Duct desction's shape - circular or rectangular",
                type:"string",
                pattern:"^circular|rectangular$",
                example:"circular",
                nullable:"true"

            ),
            new OA\Property(
                property:"flowrate",
                title:"flow rate",
                description:"Duct section's flow rate _ in cubic meter per hour (m³/h)",
                type:"integer",
                minimum:100,
                example:2500,
                nullable:"true"

            ),
            new OA\Property(
                property:"length",
                title:"length",
                description:"Duct section's linear length - in meter (m)",
                type:"float",
                format:"float",
                minimum:0.1,
                example:5.4,
                nullable:"true"

            ),
            new OA\Property(
                property:"singularities",
                title:"singularities",
                description:"List and amount of singularities in the duct section",
                type:"object",
                anyOf:[new OA\Schema(ref:"#/components/schemas/singularitiesAmount")],
                nullable:"true"

            ),
            new OA\Property(
                property:"additionalApd",
                title:"additional apd",
                description:"Optional additional air pressure drop value who represent an accessory of duct section - in pascal (Pa)",
                type:"integer",
                minimum:1,
                example:50,
                nullable:"true"
            ),
            new OA\Property(
                property:"diameter",
                oneOf:[new OA\Schema(ref:"#/components/schemas/diameters")],
                title:"diameter",
                description:"Duct section's diameter when shape is circular (normalized diameter) - in millimeter (mm)",
                type:"integer",
                nullable:"true",
                example:315
            ),
            new OA\Property(
                property:"width",
                title:"width",
                description:"Duct section's width when shape is rectangular - in millimeter (mm)",
                type:"integer",
                nullable:"true",
                example:400
            ),
            new OA\Property(
                property:"height",
                title:"height",
                description:"Duct section's height when shape is rectangular - in millimeter (mm)",
                type:"integer",
                nullable:"true",
                example:300
            ),
        ]
    )
)]
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