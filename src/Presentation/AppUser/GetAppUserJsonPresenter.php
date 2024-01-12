<?php

namespace App\Presentation\AppUser;

use App\Domain\AppUser\UseCase\GetAppUser\GetAppUserPresenter;
use App\Domain\AppUser\UseCase\GetAppUser\GetAppUserResponse;
use App\Presentation\CoreJsonPresenter;
use App\Presentation\JsonModel;

class GetAppUserJsonPresenter extends CoreJsonPresenter implements GetAppUserPresenter
{
    public function present(GetAppUserResponse $response): void
    {
        if ($response->getAppUser() === null) {
            $this->jsonModel = new JsonModel(
                'error',
                ["There is no AppUser with this id"],
                404
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