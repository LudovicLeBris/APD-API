<?php

namespace App\Presentation\AppUser;

use App\Domain\AppUser\UseCase\ConfirmRegister\ConfirmRegisterPresenter;
use App\Domain\AppUser\UseCase\ConfirmRegister\ConfirmRegisterResponse;
use App\Presentation\CoreJsonPresenter;
use App\Presentation\JsonModel;

class ConfirmRegisterJsonPresenter extends CoreJsonPresenter implements ConfirmRegisterPresenter
{
    public function present(ConfirmRegisterResponse $response): void
    {
        if ($response->getAppUser() === null) {
            $this->jsonModel = new JsonModel(
                'error',
                $response->getErrors(),
                409
            );
        } else {
            $this->jsonModel = new JsonModel(
                'success',
                ['User correctly activated'],
                200
            );
        }
    }
}