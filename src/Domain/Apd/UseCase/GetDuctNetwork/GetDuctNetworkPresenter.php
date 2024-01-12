<?php

namespace App\Domain\Apd\UseCase\GetDuctNetwork;

interface GetDuctNetworkPresenter
{
    public function present(GetDuctNetworkResponse $response): void;
}