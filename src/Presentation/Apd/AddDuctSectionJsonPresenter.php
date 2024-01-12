<?php

namespace App\Presentation\Apd;

use App\Domain\Apd\UseCase\AddDuctSection\AddDuctSectionPresenter;
use App\Domain\Apd\UseCase\AddDuctSection\AddDuctSectionResponse;
use App\Presentation\CoreJsonPresenter;
use App\Presentation\JsonModel;

class AddDuctSectionJsonPresenter extends CoreJsonPresenter implements AddDuctSectionPresenter
{
    public function present(AddDuctSectionResponse $response): void
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
                201
            );
        }
    }
}