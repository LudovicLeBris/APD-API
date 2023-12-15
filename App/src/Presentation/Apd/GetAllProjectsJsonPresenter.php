<?php

namespace App\Presentation\Apd;

use App\Presentation\JsonModel;
use App\Presentation\CoreJsonPresenter;
use App\Domain\Apd\UseCase\GetAllProjects\GetAllProjectsResponse;
use App\Domain\Apd\UseCase\GetAllProjects\GetAllProjectsPresenter;

class GetAllProjectsJsonPresenter extends CoreJsonPresenter implements GetAllProjectsPresenter
{
    public function present(GetAllProjectsResponse $response): void
    {
        if ($response->getAllProjects() === null) {
            $this->jsonModel = new JsonModel(
                'error',
                $response->getErrors(),
                422
            );
        } else {
            $this->jsonModel = new JsonModel(
                'success',
                $response->getAllProjects(),
                200
            );
        }
    }
}