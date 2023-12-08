<?php

namespace App\Presentation\AppUser;

use App\Domain\AppUser\UseCase\LostPassword\LostPasswordResponse;
use App\Domain\AppUser\UseCase\LostPassword\LostPasswordPresenter;
use App\Presentation\CoreJsonPresenter;
use App\Presentation\JsonModel;

class LostPasswordJsonPresenter extends CoreJsonPresenter implements LostPasswordPresenter
{
    public function present(LostPasswordResponse $response): void
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