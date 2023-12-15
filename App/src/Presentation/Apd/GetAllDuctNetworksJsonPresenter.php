<?php

namespace App\Presentation\Apd;

use App\Presentation\JsonModel;
use App\Presentation\CoreJsonPresenter;
use App\Domain\Apd\UseCase\GetAllDuctNetworks\GetAllDuctNetworksResponse;
use App\Domain\Apd\UseCase\GetAllDuctNetworks\GetAllDuctNetworksPresenter;

class GetAllDuctNetworksJsonPresenter extends CoreJsonPresenter implements GetAllDuctNetworksPresenter
{
    public function present(GetAllDuctNetworksResponse $response): void
    {
        if ($response->getAllDuctNetworks() === null) {
            $this->jsonModel = new JsonModel(
                'error',
                $response->getErrors(),
                422
            );
        } else {
            $this->jsonModel = new JsonModel(
                'success',
                $response->getAllDuctNetworks(),
                200
            );
        }
    }
}