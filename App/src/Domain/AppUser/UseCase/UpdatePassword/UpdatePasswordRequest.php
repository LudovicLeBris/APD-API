<?php

namespace App\Domain\AppUser\UseCase\UpdatePassword;

class UpdatePasswordRequest
{
    public $id = null;
    public $guid = null;
    public $oldPassword = null;
    public $newPassword;

    public function setContent($requestContent)
    {
        foreach ($requestContent as $field => $value) {
            if (property_exists($this, $field)) {
                $this->$field = $value;
            }
        }

        return $this;
    }

    public function setId(int $id): static
    {
        $this->id = $id;
        $this->guid = null;

        return $this;
    }

    public function setGuid(string $guid): static
    {
        $this->guid = $guid;
        $this->id = null;

        return $this;
    }
}