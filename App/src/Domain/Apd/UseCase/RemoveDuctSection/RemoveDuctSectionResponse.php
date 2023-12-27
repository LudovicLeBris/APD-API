<?php

namespace App\Domain\Apd\UseCase\RemoveDuctSection;

use App\Domain\Apd\Entity\DuctSection;
use App\Domain\CoreResponse;

class RemoveDuctSectionResponse extends CoreResponse
{
    private $ductSection;

    public function __construct() {}

    public function getDuctSection(): ?DuctSection
    {
        return $this->ductSection;
    }

    public function setDuctSection(DuctSection $ductSection): static
    {
        $this->ductSection = $ductSection;

        return $this;
    }
}