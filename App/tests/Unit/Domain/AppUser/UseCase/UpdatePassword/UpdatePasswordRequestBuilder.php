<?php

namespace App\Tests\Domain\AppUser\UseCase\UpdatePassword;

use App\Domain\AppUser\UseCase\UpdatePassword\UpdatePasswordRequest;
use App\SharedKernel\Service\TokenGenerator;

class UpdatePasswordRequestBuilder extends UpdatePasswordRequest
{
    const ID = 1;
    const GUID = null;
    const OLD_PASSWORD = 'Azerty123?';
    const NEW_PASSWORD = 'YtrezA321!';

    public static function aRecoverRequest()
    {
        $request = new static();
        $request->id = null;
        $request->guid = (new TokenGenerator())->getToken();
        $request->oldPassword = self::OLD_PASSWORD;
        $request->newPassword = self::NEW_PASSWORD;

        return $request;
    }

    public static function anUpdateRequest()
    {
        $request = new static();
        $request->id = self::ID;
        $request->guid = null;
        $request->oldPassword = null;
        $request->newPassword = self::NEW_PASSWORD;

        return $request;
    }

    public function build()
    {
        $request = new UpdatePasswordRequest();
        $request->id = $this->id;
        $request->guid = $this->guid;
        $request->oldPassword = $this->oldPassword;
        $request->newPassword = $this->newPassword;

        return $request;
    }

    public function empty()
    {
        $this->id = null;
        $this->guid = null;
        $this->oldPassword = null;
        $this->newPassword = null;
    }

    public function setOldPassword(string $oldPassword)
    {
        $this->oldPassword = $oldPassword;

        return $this;
    }

    public function setNewPassword(string $newPassword)
    {
        $this->newPassword = $newPassword;

        return $this;
    }
}