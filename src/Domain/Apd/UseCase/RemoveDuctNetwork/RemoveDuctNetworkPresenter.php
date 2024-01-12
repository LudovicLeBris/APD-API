<?php

namespace App\Domain\Apd\UseCase\RemoveDuctNetwork;

interface RemoveDuctNetworkPresenter
{
    public function present(RemoveDuctNetworkResponse $response): void;
}