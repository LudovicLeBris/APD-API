<?php

namespace App\Domain\Apd\UseCase\GetAllDuctSections;

use App\Domain\CoreResponse;

class GetAllDuctSectionsResponse extends CoreResponse
{
    private $allDuctSections;

    public function __construct() {}

    public function getAllDuctSections(): ?array
    {
        return $this->allDuctSections;
    }

    public function setAllDuctSections(?array $allDuctSections): static
    {
        $this->allDuctSections = $allDuctSections;

        return $this;
    }
}