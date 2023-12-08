<?php

namespace App\Domain\AppUser\UseCase\GetAppUser;

class GetAppUserRequest
{
    public $appUserId;

    public function __construct(int $appUserId)
    {
        $this->appUserId = $appUserId;
    }
}