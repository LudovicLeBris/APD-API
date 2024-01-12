<?php

namespace App\Domain\Apd\UseCase\RemoveDuctSection;

class RemoveDuctSectionRequest
{
    public $ductNetworkId;
    public $ductSectionId;

    public function __construct($ductNetworkId, $ductSectionId)
    {
        $this->ductNetworkId = $ductNetworkId;
        $this->ductSectionId = $ductSectionId;
    }
}