<?php

namespace App\Presentation\Apd;

use App\Domain\Apd\UseCase\AddProject\AddProjectPresenter;
use App\Domain\Apd\UseCase\AddProject\AddProjectResponse;
use App\Presentation\CoreJsonPresenter;
use App\Presentation\JsonModel;

class AddProjectJsonPresenter extends CoreJsonPresenter implements AddProjectPresenter
{
    public function present(AddProjectResponse $response): void
    {
        if ($response->getProject() === null) {
            $this->jsonModel = new JsonModel(
                'error',
                $response->getErrors(),
                422
            );
        } else {
            $this->jsonModel = new JsonModel(
                'success',
                $response->getProject(),
                201
            );
        }
    }
}