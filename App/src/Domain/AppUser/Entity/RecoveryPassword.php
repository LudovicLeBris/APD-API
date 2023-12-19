<?php

namespace App\Domain\AppUser\Entity;

use DateInterval;
use DateTimeImmutable;

class RecoveryPassword
{
    public string $guid;
    private int $appUserId;
    private string $appUserEmail;
    private DateTimeImmutable $requestDateTime;
    private bool $isEnable;

    public function __construct(
        string $guid,
        int $appUserId,
        string $appUserEmail,
        DateTimeImmutable $requestDateTime,
    )
    {
        $this->guid = $guid;
        $this->appUserId = $appUserId;
        $this->appUserEmail = $appUserEmail;
        $this->requestDateTime = $requestDateTime;

        $expectedTime = $this->requestDateTime->add(new DateInterval('PT15M'));

        if (new DateTimeImmutable() >= $expectedTime) {
            $this->isEnable = false;
        } else {
            $this->isEnable = true;
        }
    }

    public function getAppUserId()
    {
        return $this->appUserId;
    }

    public function getAppUserEmail()
    {
        return $this->appUserEmail;
    }

    public function getRequestDateTime()
    {
        return $this->requestDateTime;
    }

    public function getGuid()
    {
        return $this->guid;
    }

    public function getIsEnable()
    {
        return $this->isEnable;
    }

    public function setGuid(string $guid)
    {
        $this->guid = $guid;

        return $this;
    }
}