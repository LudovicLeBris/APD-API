<?php

namespace App\Domain\Apd\UseCase\GetAllDuctSections;

interface GetAllDuctSectionsPresenter
{
    public function present(GetAllDuctSectionsResponse $response): void;
}