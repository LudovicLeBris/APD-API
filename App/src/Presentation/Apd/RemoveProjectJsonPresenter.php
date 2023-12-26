<?php

namespace App\Presentation\Apd;

use App\Domain\Apd\UseCase\RemoveProject\RemoveProjectPresenter;
use App\Domain\Apd\UseCase\RemoveProject\RemoveProjectResponse;
use App\Presentation\CoreJsonPresenter;
use App\Presentation\JsonModel;

class RemoveProjectJsonPresenter extends CoreJsonPresenter implements RemoveProjectPresenter
{
    public function present(RemoveProjectResponse $response): void
    {
        if ($response->getProject() === null) {
            $this->jsonModel = new JsonModel(
                'error',
                $response->getErrors(),
                $response->getHttpStatus(),
            );
        } else {
            $project = $response->getProject();

            $this->jsonModel = new JsonModel(
                "Project with name \"". $project->getName() ."\" has been deleted, all associated duct networks and duct sections has been deletes too.",
                $project,
                200
            );
        }
    }
}