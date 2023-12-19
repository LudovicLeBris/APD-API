<?php

namespace App\Presentation\Apd;

use App\Domain\Apd\UseCase\GetProject\GetProjectPresenter;
use App\Domain\Apd\UseCase\GetProject\GetProjectResponse;
use App\Presentation\CoreJsonPresenter;
use App\Presentation\JsonModel;

class GetProjectJsonPresenter extends CoreJsonPresenter implements GetProjectPresenter
{
    public function present(GetProjectResponse $response): void
    {
        if ($response->getProject() === null) {
            $this->jsonModel = new JsonModel(
                'error',
                $response->getErrors(),
                404
            );
        } else {
            $this->jsonModel = new JsonModel(
                'success',
                $response->getProject(),
                200
            );
        }

    }
}