<?php

namespace App\Presentation\AppUser;

use App\Domain\AppUser\UseCase\UpdatePassword\UpdatePasswordResponse;
use App\Domain\AppUser\UseCase\UpdatePassword\UpdatePasswordPresenter;
use App\Presentation\CoreJsonPresenter;
use App\Presentation\JsonModel;

class UpdatePasswordJsonPresenter extends CoreJsonPresenter implements UpdatePasswordPresenter
{
    public function present(UpdatePasswordResponse $response): void
    {
        if ($response->getIsDone()) {
            $this->jsonModel = new JsonModel(
                'success',
                ["Password updated"],
                200
            );
        } else {
            $this->jsonModel = new JsonModel(
                'error',
                $response->getErrors(),
                422
            );
        }
    }
}