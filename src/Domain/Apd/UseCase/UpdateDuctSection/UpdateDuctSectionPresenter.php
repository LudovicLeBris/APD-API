<?php

namespace App\Domain\Apd\UseCase\UpdateDuctSection;

interface UpdateDuctSectionPresenter
{
    public function present(UpdateDuctSectionResponse $response): void;
}