<?php

namespace App\Domain\Apd\UseCase\GetDuctSection;

interface GetDuctSectionPresenter
{
    public function present(GetDuctSectionResponse $response): void;
}