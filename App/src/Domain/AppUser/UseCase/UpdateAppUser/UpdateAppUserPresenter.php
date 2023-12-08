<?php

namespace App\Domain\AppUser\UseCase\UpdateAppUser;

interface UpdateAppUserPresenter
{
    public function present(UpdateAppUserResponse $response): void;
}