<?php

namespace App\Presentation\Apd;

use App\Domain\Apd\UseCase\GetDuctNetwork\GetDuctNetworkPresenter;
use App\Domain\Apd\UseCase\GetDuctNetwork\GetDuctNetworkResponse;
use App\Presentation\CoreJsonPresenter;
use App\Presentation\JsonModel;

class GetDuctNetworkJsonPresenter extends CoreJsonPresenter implements GetDuctNetworkPresenter
{
    public function present(GetDuctNetworkResponse $response): void
    {
        if ($response->getDuctNetwork() === null) {
            $this->jsonModel = new JsonModel(
                'error',
                $response->getErrors(),
                $response->getHttpStatus(),
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