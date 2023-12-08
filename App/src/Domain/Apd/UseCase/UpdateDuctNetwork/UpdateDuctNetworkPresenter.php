<?php

namespace App\Domain\Apd\UseCase\UpdateDuctNetwork;

interface UpdateDuctNetworkPresenter
{
    public function present(UpdateDuctNetworkResponse $response): void;
}