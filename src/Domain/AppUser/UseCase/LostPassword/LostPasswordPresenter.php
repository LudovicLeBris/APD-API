<?php

namespace App\Domain\AppUser\UseCase\LostPassword;

interface LostPasswordPresenter
{
    public function present(LostPasswordResponse $response): void;
}