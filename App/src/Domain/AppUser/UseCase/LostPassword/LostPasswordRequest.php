<?php

namespace App\Domain\AppUser\UseCase\LostPassword;

class LostPasswordRequest
{
    public $email = null;

    public function __construct(string $email)
    {
        $this->email = $email;
    }
}