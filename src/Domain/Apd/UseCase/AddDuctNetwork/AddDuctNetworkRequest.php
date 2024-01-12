<?php

namespace App\Domain\Apd\UseCase\AddDuctNetwork;

use OpenApi\Attributes as OA;

#[OA\RequestBody(
    request:"addDuctNetwork",
    required:"true",
    content: new OA\JsonContent(
        properties:[
            new OA\Property(
                property:"name",
                title:"name",
                description:"Name of the duct network",
                type:"string",
                example:"duct network n°1",
            ),
            new OA\Property(
                property:"generalMaterial",
                oneOf:[new OA\Schema(ref:"#/components/schemas/materialsName")],
                title:"general material",
                description:"Duct network's material, all duct sections are dependent of this property",
                type:"string",
                example:"galvanised_steel",
            ),
            new OA\Property(
                property:"additionalApd",
                title:"additional apd",
                description:"Optional additional air pressure drop value who represent an accessory of duct network - in pascal (Pa)",
                type:"integer",
                minimum:1,
                example:50,
            ),
        ]
    )
)]
class AddDuctNetworkRequest
{
    public $name;
    public $projectId;

    public $generalMaterial;
    public $additionalApd = 0;

    public function __construct($projectId)
    {
        $this->projectId = $projectId;
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