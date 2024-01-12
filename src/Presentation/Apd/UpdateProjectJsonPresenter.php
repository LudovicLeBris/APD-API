<?php

namespace App\Presentation\Apd;

use App\Domain\Apd\UseCase\UpdateProject\UpdateProjectResponse;
use App\Domain\Apd\UseCase\UpdateProject\UpdateProjectPresenter;
use App\Presentation\CoreJsonPresenter;
use App\Presentation\JsonModel;

class UpdateProjectJsonPresenter extends CoreJsonPresenter implements UpdateProjectPresenter
{
    public function present(UpdateProjectResponse $response): void
    {
        if ($response->getProject() === null) {
            $this->jsonModel = new JsonModel(
                'error',
                $response->getErrors(),
                $response->getHttpStatus(),
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