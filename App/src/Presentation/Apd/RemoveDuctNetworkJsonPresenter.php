<?php

namespace App\Presentation\Apd;

use App\Domain\Apd\UseCase\RemoveDuctNetwork\RemoveDuctNetworkPresenter;
use App\Domain\Apd\UseCase\RemoveDuctNetwork\RemoveDuctNetworkResponse;
use App\Presentation\CoreJsonPresenter;
use App\Presentation\JsonModel;

class RemoveDuctNetworkJsonPresenter extends CoreJsonPresenter implements RemoveDuctNetworkPresenter
{
    public function present(RemoveDuctNetworkResponse $response): void
    {
        if ($response->getDuctNetwork() === null) {
            $this->jsonModel = new JsonModel(
                'error',
                $response->getErrors(),
                $response->getHttpStatus(),
            );
        } else {
            $ductNetwork = $response->getDuctNetwork();

            $this->jsonModel = new JsonModel(
                "DuctNetwork with name ". $ductNetwork->getName() ." has been deleted, all associated duct sections has been deletes too.",
                $ductNetwork,
                200
            );
        }
    }
}