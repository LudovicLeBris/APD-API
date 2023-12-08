<?php

namespace App\Domain\Apd\UseCase\RemoveDuctNetwork;

use App\Domain\Apd\Entity\DuctNetwork;
use App\Domain\CoreResponse;

class RemoveDuctNetworkResponse extends CoreResponse
{
    private $ductNetwork;

    public function __construct() {}

    public function getDuctNetwork(): ?DuctNetwork
    {
        return $this->ductNetwork;
    }

    public function setDuctNetwork(DuctNetwork $ductNetwork): static
    {
        $this->ductNetwork = $ductNetwork;

        return $this;
    }
}