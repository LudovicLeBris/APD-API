<?php

namespace App\Domain\AppUser\UseCase\ConfirmRegister;

interface ConfirmRegisterPresenter
{
    public function present(ConfirmRegisterResponse $response): void;
}