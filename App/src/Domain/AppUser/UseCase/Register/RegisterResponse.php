<?php

namespace App\Domain\AppUser\UseCase\Register;

use App\Domain\AppUser\Entity\AppUser;
use App\Domain\CoreResponse;

class RegisterResponse extends CoreResponse
{
    private $appUser;

    public function __construct() {}

    public function getAppUser(): ?AppUser
    {
        return $this->appUser;
    }

    public function setAppUser(AppUser $techncian): static
    {
        $this->appUser = $techncian;

        return $this;
    }
}