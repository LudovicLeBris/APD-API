<?php

namespace App\Presentation\AppUser;

use App\Domain\AppUser\UseCase\Register\RegisterPresenter;
use App\Domain\AppUser\UseCase\Register\RegisterResponse;
use App\Presentation\CoreJsonPresenter;
use App\Presentation\JsonModel;

class RegisterJsonPresenter extends CoreJsonPresenter implements RegisterPresenter
{
    public function present(RegisterResponse $response): void
    {
        if ($response->getAppUser() === null) {
            $this->jsonModel = new JsonModel(
                'error',
                $response->getErrors(),
                422
            );
        } else {
            $this->jsonModel = new JsonModel(
                'success',
                $response->getAppUser(),
                200
            );
        }
    }
}