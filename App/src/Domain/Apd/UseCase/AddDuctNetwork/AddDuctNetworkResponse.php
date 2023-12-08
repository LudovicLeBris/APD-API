<?php

namespace App\Domain\Apd\UseCase\AddDuctNetwork;

use App\Domain\Apd\Entity\DuctNetwork;
use App\Domain\CoreResponse;

class AddDuctNetworkResponse extends CoreResponse
{
    private $ductNetwork;

    public function __construct() {}
    
    public function getDuctNetwork(): ?DuctNetwork
    {
        return $this->ductNetwork;
    }

    public function setDuctNetwork(DuctNetwork $ducNetwork): static
    {
        $this->ductNetwork = $ducNetwork;

        return $this;
    }
}