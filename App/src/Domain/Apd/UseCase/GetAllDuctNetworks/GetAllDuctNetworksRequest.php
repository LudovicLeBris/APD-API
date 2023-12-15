<?php

namespace App\Domain\Apd\UseCase\GetAllDuctNetworks;

class GetAllDuctNetworksRequest
{
    public $projectId;

    public function __construct(int $projectId)
    {
        $this->projectId = $projectId;
    }
}