<?php

namespace App\Domain\Apd\UseCase\GetDuctSection;

class GetDuctSectionRequest
{
    public $id;

    public function __construct($id)
    {
        $this->id = $id;
    }
}