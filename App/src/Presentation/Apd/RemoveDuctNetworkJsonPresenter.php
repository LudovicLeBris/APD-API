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
                404
            );
        } else {
            $ductNetwork = $response->getDuctNetwork();

            $this->jsonModel = new JsonModel(
                "DuctNetwork with name ". $ductNetwork->getName() ." has been deleted",
                $ductNetwork,
                200
            );
        }
    }
}