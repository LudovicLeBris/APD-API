<?php

namespace App\Domain\Apd\UseCase\AddProject;

class AddProjectRequest
{
    public $name;

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