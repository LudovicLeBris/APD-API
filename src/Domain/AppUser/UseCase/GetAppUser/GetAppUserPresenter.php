<?php

namespace App\Domain\AppUser\UseCase\GetAppUser;

interface GetAppUserPresenter
{
    public function present(GetAppUserResponse $response): void;
}