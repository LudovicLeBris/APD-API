<?php

namespace App\Domain\Apd\UseCase\GetAllDuctNetworks;

use App\Domain\CoreResponse;

class GetAllDuctNetworksResponse extends CoreResponse
{
    private $allDuctNetworks;

    public function __construct() {}

    public function getAllDuctNetworks(): ?array
    {
        return $this->allDuctNetworks;
    }

    public function setAllDuctNetworks(?array $allDuctNetworks): static
    {
        $this->allDuctNetworks = $allDuctNetworks;

        return $this;
    }
}