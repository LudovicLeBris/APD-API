<?php

namespace App\Tests\Domain\AppUser\Entity;

use App\Domain\AppUser\Entity\RecoveryPassword;
use App\SharedKernel\Service\TokenGenerator;
use DateTimeImmutable;

class RecoveryPasswordBuilder
{
    private $guid = null;
    private $appUserId = 1;
    private $appUserEmail = 'email@test.io';
    private $requestDateTime = null;
    private $isEnable = true;

    public function build()
    {
        $this->guid = (new TokenGenerator())->getToken();
        $this->requestDateTime = new DateTimeImmutable();

        $recoveryPassword = new RecoveryPassword(
            $this->guid,
            $this->appUserId,
            $this->appUserEmail,
            $this->requestDateTime,
        );

        return $recoveryPassword;
    }

    public static function aRecoveryPassword()
    {
        return new RecoveryPasswordBuilder;
    }

    public function setGuid(string $guid)
    {
        $this->guid = $guid;

        return $this;
    }

    public function setAppUserId(int $appUserId)
    {
        $this->appUserId = $appUserId;

        return $this;
    }
    
    public function setAppUserEmail(string $appUserEmail)
    {
        $this->appUserEmail = $appUserEmail;

        return $this;
    }

    public function setRequestDateTime(DateTimeImmutable $requestDateTime)
    {
        $this->requestDateTime = $requestDateTime;

        return $this;
    }


    public function setIsEnable(bool $isEnable)
    {
        $this->isEnable = $isEnable;

        return $this;
    }
}