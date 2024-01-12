<?php

namespace App\Domain\Apd\UseCase\GetDuctNetwork;

class GetDuctNetworkRequest
{
    public $projectId;
    public $ductNetworkId;

    public function __construct($projectId, $ductNetworkId)
    {
        $this->projectId = $projectId;
        $this->ductNetworkId = $ductNetworkId;
    }
}