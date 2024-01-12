<?php

namespace App\Domain\Apd\UseCase\UpdateDuctNetwork;

use OpenApi\Attributes as OA;

#[OA\RequestBody(
    request:"updateDuctNetwork",
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
                property:"generalMaterial",
                title:"general material",
                description:"Duct network's material, all duct sections are dependent of this property",
                type:"string",
                example:"galvanised_steel",
                nullable:"true"
            ),
            new OA\Property(
                property:"additionalApd",
                title:"additional apd",
                description:"Optional additional air pressure drop value who represent an accessory of duct network - in pascal (Pa)",
                type:"integer",
                minimum:1,
                example:50,
                nullable:"true"
            ),
            new OA\Property(
                property:"altitude",
                title:"altitude",
                description:"Duct network's altitude below sea level, all duct sections are dependant of this property - in meter (m)",
                type:"integer",
                minimum:0,
                example:800,
                nullable:"true"
            ),
            new OA\Property(
                property:"temperature",
                title:"temperature",
                description:"Duct network's temperature, all duct sections are dependant of this property - in meter (m",
                type:"number",
                format:"float",
                example:18.2,
                nullable:"true"
            ),
        ]
    )
)]
class UpdateDuctNetworkRequest
{
    public $id;
    public $name;
    public $projectId;

    public $altitude = null;
    public $temperature = null;

    public $generalMaterial;
    public $additionalApd = null;

    public function __construct($projectId, $ductNetworkId)
    {
        $this->projectId = $projectId;
        $this->id = $ductNetworkId;
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
}