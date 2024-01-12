<?php

namespace App\Domain\AppUser\UseCase\RemoveAppUser;

interface RemoveAppUserPresenter
{
    public function present(RemoveAppUserResponse $response): void;
}