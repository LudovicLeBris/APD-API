<?php

namespace App\Domain\Apd\UseCase\GetDuctNetwork;

use App\Domain\Apd\Entity\DuctNetwork;
use App\Domain\CoreResponse;

class GetDuctNetworkResponse extends CoreResponse
{
    private $ductNetwork;

    public function __construct() {}

    public function getDuctNetwork(): ?DuctNetwork
    {
        return $this->ductNetwork;
    }

    public function setDuctNetwork(?DuctNetwork $ductNetwork): static
    {
        $this->ductNetwork = $ductNetwork;

        return $this;
    }
}