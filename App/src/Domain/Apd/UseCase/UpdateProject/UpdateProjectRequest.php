<?php

namespace App\Domain\Apd\UseCase\UpdateProject;

use OpenApi\Attributes as OA;

#[OA\RequestBody(
    request:"updateProject",
    required:"true",
    content: new OA\JsonContent(
        properties:[
            new OA\Property(
                property:"name",
                title:"name",
                description:"Name of the project",
                type:"string",
                example:"project B",
                nullable:"true"
            ),
            new OA\Property(
                property:"generalAltitude",
                title:"general altitude",
                description:"Project's altitude below sea level, all duct networks and duct sections are dependant of this property - in meter (m",
                type:"integer",
                minimum:0,
                example:800,
                nullable:"true"
            ),
            new OA\Property(
                property:"generalTemperature",
                title:"general temperature",
                description:"Project's temperature, all duct networks and duct sections are dependant of this property - in degrees Celsius (°C)",
                type:"number",
                format:"float",
                example:18.2,
                nullable:"true"
            ),
        ]
    )
)]
class UpdateProjectRequest
{
    public $userId;
    public $projectId;
    public $name;

    public $generalAltitude;
    public $generalTemperature;

    public function __construct(int $userId, int $projectId)
    {
        $this->userId = $userId;
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