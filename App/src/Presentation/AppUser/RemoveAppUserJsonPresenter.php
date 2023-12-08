<?php

namespace App\Presentation\AppUser;

use App\Domain\AppUser\UseCase\RemoveAppUser\RemoveAppUserPresenter;
use App\Domain\AppUser\UseCase\RemoveAppUser\RemoveAppUserResponse;
use App\Presentation\CoreJsonPresenter;
use App\Presentation\JsonModel;

class RemoveAppUserJsonPresenter extends CoreJsonPresenter implements RemoveAppUserPresenter
{
    public function present(RemoveAppUserResponse $response): void
    {
        if ($response->getAppUser() === null) {
            $this->jsonModel = new JsonModel(
                'error',
                $response->getErrors(),
                404
            );
        } else {
            $appAppUser = $response->getAppUser();

            $this->jsonModel = new JsonModel(
                "User ". $appAppUser->getEmail() ." and all projects are deleted",
                $appAppUser,
                200
            );
        }
    }
}   