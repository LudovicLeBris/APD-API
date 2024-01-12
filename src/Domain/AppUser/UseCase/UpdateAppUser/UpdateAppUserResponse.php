<?php

namespace App\Domain\AppUser\UseCase\UpdateAppUser;

use App\Domain\AppUser\Entity\AppUser;
use App\Domain\CoreResponse;

class UpdateAppUserResponse extends CoreResponse
{
    private $appUser;

    public function __construct() {}

    public function getAppUser(): ?AppUser
    {
        return $this->appUser;
    }

    public function setAppUser(AppUser $appUser): static
    {
        $this->appUser = $appUser;

        return $this;
    }
}