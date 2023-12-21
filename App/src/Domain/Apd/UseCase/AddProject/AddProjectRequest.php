<?php

namespace App\Domain\Apd\UseCase\AddProject;

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