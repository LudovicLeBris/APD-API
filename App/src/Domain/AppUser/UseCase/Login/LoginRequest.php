<?php

namespace App\Domain\AppUser\UseCase\Login;

class LoginRequest
{
    public $email;
    public $password;

    public function __construct(string $email, string $password)
    {
        $this->email = $email;
        $this->password = $password;
    }
}