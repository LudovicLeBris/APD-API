<?php

namespace App\Presentation\AppUser;

use App\Domain\AppUser\UseCase\UpdateAppUser\UpdateAppUserPresenter;
use App\Domain\AppUser\UseCase\UpdateAppUser\UpdateAppUserResponse;
use App\Presentation\CoreJsonPresenter;
use App\Presentation\JsonModel;

class UpdateAppUserJsonPresenter extends CoreJsonPresenter implements UpdateAppUserPresenter
{
    public function present(UpdateAppUserResponse $response): void
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