<?php

namespace App\Domain\Apd\UseCase\UpdateDuctNetwork;

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