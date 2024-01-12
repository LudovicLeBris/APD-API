<?php

namespace App\Domain\Apd\UseCase\AddDuctSection;

use App\Domain\Apd\Entity\DuctSection;
use App\Domain\CoreResponse;

class AddDuctSectionResponse extends CoreResponse
{
    private $ductSection;

    public function __construct() {}

    public function getDuctSection(): ?DuctSection
    {
        return $this->ductSection;
    }

    public function setDuctSection(DuctSection $ducSection): static
    {
        $this->ductSection = $ducSection;

        return $this;
    }
}