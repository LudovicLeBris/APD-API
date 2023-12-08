<?php

namespace App\Domain\AppUser\Entity;

interface RecoveryPasswordRepositoryInterface
{
    public function getRecoveryPasswordByGuid(string $guid): ?RecoveryPassword;

    public function getRecoveryPasswordByAppUserId(int $AppUserId): ?RecoveryPassword;

    public function addRecoveryPassword(RecoveryPassword $recoveryPassword): void;

    public function deleteRecoveryPassword(string $guid): void;
}