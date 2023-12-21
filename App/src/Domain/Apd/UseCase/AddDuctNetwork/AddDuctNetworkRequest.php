<?php

namespace App\Domain\Apd\UseCase\AddDuctNetwork;

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