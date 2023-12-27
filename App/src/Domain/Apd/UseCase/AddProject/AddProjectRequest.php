<?php

namespace App\Domain\Apd\UseCase\AddProject;

use OpenApi\Attributes as OA;

#[OA\RequestBody(
    request:"addProject",
    required:"true",
    content: new OA\JsonContent(
        properties:[
            new OA\Property(
                property:"name",
                title:"name",
                description:"Name of the project",
                type:"string",
                example:"project B",
            )
        ]
    )
)]
class AddProjectRequest
{
    public $userId;
    public $name;

    public function __construct(int $userId)
    {
        $this->userId = $userId;
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