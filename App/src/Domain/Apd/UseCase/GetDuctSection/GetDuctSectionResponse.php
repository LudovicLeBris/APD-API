<?php

namespace App\Domain\Apd\UseCase\GetDuctSection;

use App\Domain\Apd\Entity\DuctSection;

class GetDuctSectionResponse
{
    private $ductSection;

    public function getDuctSection(): ?DuctSection
    {
        return $this->ductSection;
    }

    public function setDuctSection(?DuctSection $ducSection): static
    {
        $this->ductSection = $ducSection;

        return $this;
    }
}