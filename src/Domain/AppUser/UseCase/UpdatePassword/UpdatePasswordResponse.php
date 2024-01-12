<?php

namespace App\Domain\AppUser\UseCase\UpdatePassword;

use App\Domain\CoreResponse;

class UpdatePasswordResponse extends CoreResponse
{
    private $isDone = false;

    public function __construct() {}

    public function getIsDone(): bool
    {
        return $this->isDone;
    }

    public function setIsDone(bool $isDone): static
    {
        $this->isDone = $isDone;

        return $this;
    }
}