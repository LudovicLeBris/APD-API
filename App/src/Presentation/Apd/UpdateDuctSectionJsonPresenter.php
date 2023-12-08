<?php

namespace App\Presentation\Apd;

use App\Domain\Apd\UseCase\UpdateDuctSection\UpdateDuctSectionResponse;
use App\Domain\Apd\UseCase\UpdateDuctSection\UpdateDuctSectionPresenter;
use App\Presentation\CoreJsonPresenter;
use App\Presentation\JsonModel;

class UpdateDuctSectionJsonPresenter extends CoreJsonPresenter implements UpdateDuctSectionPresenter
{
    public function present(UpdateDuctSectionResponse $response): void
    {
        if ($response->getDuctSection() === null) {
            $this->jsonModel = new JsonModel(
                'error',
                $response->getErrors(),
                422
            );
        } else {
            $this->jsonModel = new JsonModel(
                'success',
                $response->getDuctSection(),
                200
            );
        }
    }
}