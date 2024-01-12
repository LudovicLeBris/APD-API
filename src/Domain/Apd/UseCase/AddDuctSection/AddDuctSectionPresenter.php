<?php

namespace App\Domain\Apd\UseCase\AddDuctSection;

interface AddDuctSectionPresenter
{
    public function present(AddDuctSectionResponse $response): void;
}