<?php

namespace App\Tests\_Mock\Domain\AppUser\Entity;

use App\Domain\AppUser\Entity\AppUser;
use App\Domain\AppUser\Entity\AppUserRepositoryInterface;

class InMemoryAppUserRepository implements AppUserRepositoryInterface
{
    private $appUsers = [];
    
    public function getAllAppUsers()
    {
        return $this->appUsers;
    }
    
    public function getAppUserByEmail(string $email): ?AppUser
    {
        $find = function (AppUser $appUser) use ($email) {
            return $appUser->getEmail() === $email;
        };

        $appUsersFound = array_values(array_filter($this->appUsers, $find));
        if(count($appUsersFound) === 1) {
            return $appUsersFound[0];
        }
        
        return null;
    }

    public function getAppUserById(int $id): ?AppUser
    {
        $find = function (AppUser $appUser) use ($id) {
            return $appUser->getId() === $id;
        };

        $appUsersFound = array_values(array_filter($this->appUsers, $find));
        if(count($appUsersFound) === 1) {
            return $appUsersFound[0];
        }
        
        return null;
    }

    public function addAppUser(AppUser $appUser): void
    {
        if (!isset($appUser->id)) {
            $appUser->setId(mt_rand(0, 500));
        }
        
        $this->appUsers[] = $appUser;
    }

    public function updateAppUser(AppUser $appUser): void
    {
        for ($i=0; $i < count($this->appUsers); $i++) {
            if ($this->appUsers[$i]->getId() === $appUser->getId()) {
                $this->appUsers[$i] = $appUser;
                break;
            }
        }
    }

    public function deleteAppUser(int $id): void
    {
        for ($i=0; $i < count($this->appUsers); $i++) {
            if ($this->appUsers[$i]->getId() === $id) {
                array_splice($this->appUsers, $i, 1);
                break;
            }
        }
    }
}