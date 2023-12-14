<?php

namespace App\Domain\AppUser\UseCase\GetAppUser;

use App\Domain\AppUser\Entity\AppUser;

class GetAppUserResponse
{
    private $appUser;

    public function getAppUser(): ?AppUser
    {
        return $this->appUser;
    }

    public function setAppUser(?AppUser $appUser): static
    {
        $this->appUser = $appUser;

        return $this;
    }
}