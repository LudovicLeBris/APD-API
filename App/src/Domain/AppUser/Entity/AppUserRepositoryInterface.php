<?php

namespace App\Domain\AppUser\Entity;

interface AppUserRepositoryInterface
{
    public function getAppUserByEmail(string $email): ?AppUser;

    public function getAppUserById(int $id): ?AppUser;

    public function addAppUser(AppUser $appUser): void;

    public function updateAppUser(AppUser $appUser): void;

    public function deleteAppUser(string $id): void;
}