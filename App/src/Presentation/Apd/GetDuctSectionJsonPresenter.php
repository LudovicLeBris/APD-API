<?php

namespace App\Presentation\Apd;

use App\Domain\Apd\UseCase\GetDuctSection\GetDuctSectionPresenter;
use App\Domain\Apd\UseCase\GetDuctSection\GetDuctSectionResponse;
use App\Presentation\CoreJsonPresenter;
use App\Presentation\JsonModel;

class GetDuctSectionJsonPresenter extends CoreJsonPresenter implements GetDuctSectionPresenter
{
    public function present(GetDuctSectionResponse $response): void
    {
        if ($response->getDuctSection() === null) {
            $this->jsonModel = new JsonModel(
                'error',
                $response->getErrors(),
                $response->getHttpStatus(),
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