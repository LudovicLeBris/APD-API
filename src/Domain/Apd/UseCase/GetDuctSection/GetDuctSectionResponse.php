<?php

namespace App\Domain\Apd\UseCase\GetDuctSection;

use App\Domain\Apd\Entity\DuctSection;
use App\Domain\CoreResponse;

class GetDuctSectionResponse extends CoreResponse
{
    private $ductSection;

    public function __construct() {}

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