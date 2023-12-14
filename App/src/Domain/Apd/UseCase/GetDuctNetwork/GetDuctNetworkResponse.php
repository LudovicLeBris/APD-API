<?php

namespace App\Domain\Apd\UseCase\GetDuctNetwork;

use App\Domain\Apd\Entity\DuctNetwork;

class GetDuctNetworkResponse
{
    private $ductNetwork;

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