<?php

namespace App\Presentation\Apd;

use App\Domain\Apd\UseCase\GetAllDuctSections\GetAllDuctSectionsPresenter;
use App\Domain\Apd\UseCase\GetAllDuctSections\GetAllDuctSectionsResponse;
use App\Presentation\CoreJsonPresenter;
use App\Presentation\JsonModel;

class GetAllDuctSectionsJsonPresenter extends CoreJsonPresenter implements GetAllDuctSectionsPresenter
{
    public function present(GetAllDuctSectionsResponse $response): void
    {
        if ($response->getAllDuctSections() === null) {
            $this->jsonModel = new JsonModel(
                'error',
                $response->getErrors(),
                $response->getHttpStatus(),
            );
        } else {
            $this->jsonModel = new JsonModel(
                'success',
                $response->getAllDuctSections(),
                200
            );
        }
    }
}