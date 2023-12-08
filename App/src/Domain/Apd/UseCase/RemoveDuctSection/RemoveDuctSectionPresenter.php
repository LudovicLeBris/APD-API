<?php

namespace App\Domain\Apd\UseCase\RemoveDuctSection;

Interface RemoveDuctSectionPresenter
{
    public function present(RemoveDuctSectionResponse $response): void;
}