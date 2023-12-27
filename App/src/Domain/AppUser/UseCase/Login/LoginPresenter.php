<?php

namespace App\Domain\AppUser\UseCase\Login;

interface LoginPresenter
{
    public function present(LoginResponse $response): void;
}