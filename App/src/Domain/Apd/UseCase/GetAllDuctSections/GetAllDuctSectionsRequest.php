<?php

namespace App\Domain\Apd\UseCase\GetAllDuctSections;

class GetAllDuctSectionsRequest
{
    public $ductNetworkId;

    public function __construct(int $ductNetworkId)
    {
        $this->ductNetworkId = $ductNetworkId;
    }
}