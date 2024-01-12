<?php

namespace App\Domain\Apd\UseCase\AddDuctNetwork;

use App\Domain\Apd\UseCase\AddDuctNetwork\AddDuctNetworkResponse;

interface AddDuctNetworkPresenter
{
    public function present(AddDuctNetworkResponse $response): void;
}