<?php

namespace App\Domain\AppUser\UseCase\UpdatePassword;

interface UpdatePasswordPresenter
{
    public function present(UpdatePasswordResponse $response): void;
}