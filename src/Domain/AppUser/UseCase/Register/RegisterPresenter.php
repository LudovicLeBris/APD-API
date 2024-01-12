<?php

namespace App\Domain\AppUser\UseCase\Register;

interface RegisterPresenter
{
    public function present(RegisterResponse $response): void;
}