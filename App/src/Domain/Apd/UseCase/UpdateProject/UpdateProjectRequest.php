<?php

namespace App\Domain\Apd\UseCase\UpdateProject;

class UpdateProjectRequest
{
    public $id;
    public $name;

    public $generalAltitude;
    public $generalTemperature;

    public function __construct(int $projectId)
    {
        $this->id = $projectId;
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
}