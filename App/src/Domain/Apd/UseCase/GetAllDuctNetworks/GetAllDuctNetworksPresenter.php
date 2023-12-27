<?php

namespace App\Domain\Apd\UseCase\GetAllDuctNetworks;

interface GetAllDuctNetworksPresenter
{
    public function present(GetAllDuctNetworksResponse $response): void;
}