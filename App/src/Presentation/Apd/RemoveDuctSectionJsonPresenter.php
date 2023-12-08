<?php

namespace App\Presentation\Apd;

use App\Domain\Apd\UseCase\RemoveDuctSection\RemoveDuctSectionPresenter;
use App\Domain\Apd\UseCase\RemoveDuctSection\RemoveDuctSectionResponse;
use App\Presentation\CoreJsonPresenter;
use App\Presentation\JsonModel;

class RemoveDuctSectionJsonPresenter extends CoreJsonPresenter implements RemoveDuctSectionPresenter
{
    public function present(RemoveDuctSectionResponse $response): void
    {
        if ($response->getDuctSection() === null) {
            $this->jsonModel = new JsonModel(
                'error',
                $response->getErrors(),
                404
            );
        } else {
            $ductSection = $response->getDuctSection();

            $this->jsonModel = new JsonModel(
                "DuctSection with name ". $ductSection->getName() ." has been deleted",
                $ductSection,
                200
            );
        }
    }
}