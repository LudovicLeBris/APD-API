<?php

namespace App\Presentation\Apd;

use App\Domain\Apd\UseCase\AddDuctNetwork\AddDuctNetworkPresenter;
use App\Domain\Apd\UseCase\AddDuctNetwork\AddDuctNetworkResponse;
use App\Presentation\CoreJsonPresenter;
use App\Presentation\JsonModel;

class AddDuctNetworkJsonPresenter extends CoreJsonPresenter implements AddDuctNetworkPresenter
{
    public function present(AddDuctNetworkResponse $response): void
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
                201            );
        }
    }
}