<?php

namespace App\Domain\Apd\UseCase\GetDuctSection;

class GetDuctSectionRequest
{
    public $ductNetworkId;
    public $ductSectionId;

    public function __construct($ductNetworkId, $ductSectionId)
    {
        $this->ductNetworkId = $ductNetworkId;
        $this->ductSectionId = $ductSectionId;
    }
}