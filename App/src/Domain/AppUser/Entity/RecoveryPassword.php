<?php

namespace App\Domain\AppUser\Entity;

use DateInterval;
use DateTimeImmutable;

class RecoveryPassword
{
    public string $guid;
    private int $AppUserId;
    private string $AppUserEmail;
    private DateTimeImmutable $requestDateTime;
    private bool $isEnable;

    public function __construct(
        string $guid,
        int $AppUserId,
        string $AppUserEmail,
        DateTimeImmutable $requestDateTime,
    )
    {
        $this->guid = $guid;
        $this->AppUserId = $AppUserId;
        $this->AppUserEmail = $AppUserEmail;
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
        return $this->AppUserId;
    }

    public function getAppUserEmail()
    {
        return $this->AppUserEmail;
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