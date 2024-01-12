<?php

namespace App\Domain\AppUser\UseCase\RemoveAppUser;

class RemoveAppUserRequest
{
    public $id;

    public function __construct(int $appUserId)
    {
        $this->id = $appUserId;
    }
}