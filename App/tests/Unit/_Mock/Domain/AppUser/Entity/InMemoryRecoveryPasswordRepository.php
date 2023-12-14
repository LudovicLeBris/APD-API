<?php

namespace App\Tests\_Mock\Domain\AppUser\Entity;

use App\Domain\AppUser\Entity\RecoveryPassword;
use App\Domain\AppUser\Entity\RecoveryPasswordRepositoryInterface;
use App\SharedKernel\Service\TokenGenerator;

class InMemoryRecoveryPasswordRepository implements RecoveryPasswordRepositoryInterface
{
    private $recoveryPasswords = [];

    public function getRecoveryPasswordByGuid(string $guid): ?RecoveryPassword
    {
        $find = function (RecoveryPassword $recoveryPassword) use ($guid) {
            return $recoveryPassword->getGuid() === $guid;
        };

        $recoveryPasswordsFound = array_values(array_filter($this->recoveryPasswords, $find));
        if(count($recoveryPasswordsFound) === 1) {
            return $recoveryPasswordsFound[0];
        }
        
        return null;
    }

    public function getRecoveryPasswordByAppUserId(int $AppUserId): ?RecoveryPassword
    {
        $find = function (RecoveryPassword $recoveryPassword) use ($AppUserId) {
            return $recoveryPassword->getAppUserId() === $AppUserId;
        };

        $recoveryPasswordsFound = array_values(array_filter($this->recoveryPasswords, $find));
        if(count($recoveryPasswordsFound) === 1) {
            return $recoveryPasswordsFound[0];
        }
        
        return null;
    }

    public function addRecoveryPassword(RecoveryPassword $recoveryPassword): void
    {
        if (!isset($recoveryPassword->guid)) {
            $recoveryPassword->setGuid((new TokenGenerator())->getToken());
        }
        
        $this->recoveryPasswords[] = $recoveryPassword;
    }

    public function deleteRecoveryPassword(string $guid): void
    {
        for ($i=0; $i < count($this->recoveryPasswords); $i++) {
            if ($this->recoveryPasswords[$i]->getGuid() === $guid) {
                array_splice($this->recoveryPasswords, $i, 1);
                break;
            }
        }
    }
}