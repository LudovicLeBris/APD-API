<?php

namespace App\Domain\Apd\UseCase\UpdateProject;

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