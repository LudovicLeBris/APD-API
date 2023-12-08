<?php

namespace App\Presentation\Apd;

use App\Domain\Apd\UseCase\UpdateDuctNetwork\UpdateDuctNetworkResponse;
use App\Domain\Apd\UseCase\UpdateDuctNetwork\UpdateDuctNetworkPresenter;
use App\Presentation\CoreJsonPresenter;
use App\Presentation\JsonModel;

class UpdateDuctNetworkJsonPresenter extends  CoreJsonPresenter implements UpdateDuctNetworkPresenter
{
    public function present(UpdateDuctNetworkResponse $response): void
    {
        if ($response->getDuctNetwork() === null) {
            $this->jsonModel = new JsonModel(
                'error',
                $response->getErrors(),
                422
            );
        } else {
            $this->jsonModel = new JsonModel(
                'success',
                $response->getDuctNetwork(),
                200
            );
        }
    }
}