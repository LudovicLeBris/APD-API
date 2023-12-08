<?php

namespace App\Presentation\AppUser;

use App\Domain\AppUser\UseCase\Login\LoginResponse;
use App\Domain\AppUser\UseCase\Login\LoginPresenter;
use App\Presentation\CoreJsonPresenter;
use App\Presentation\JsonModel;

class LoginJsonPresenter extends CoreJsonPresenter implements LoginPresenter
{
    public function present(LoginResponse $response): void
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