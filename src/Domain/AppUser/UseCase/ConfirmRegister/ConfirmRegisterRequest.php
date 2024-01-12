<?php

namespace App\Domain\AppUser\UseCase\ConfirmRegister;

class ConfirmRegisterRequest
{
    public $id;

    public function __construct(int $id)
    {
        $this->id = $id;
    }
}