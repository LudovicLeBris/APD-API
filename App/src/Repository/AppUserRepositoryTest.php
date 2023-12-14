<?php

namespace App\Repository;

use App\Domain\AppUser\Entity\AppUser;
use Doctrine\Persistence\ManagerRegistry;
use App\Domain\AppUser\Entity\AppUserRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

class AppUserRepositoryTest extends ServiceEntityRepository implements AppUserRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AppUser::class);
    }

    public function getAppUserByEmail(string $email): ?AppUser
    {
        $appUser = new AppUser(
            "toto@toto.to",
            '$2y$10$zJ1xMnkzVB6rqYfxx0Skg.ZNre8Eye1X7t97uW9yRdHZswoIMlBJ.',
            "De Toto",
            "Toto",
            "Toto&Cie",
            "appUser",
            true
        );

        $appUser->setId(1);

        // return null;
        return $appUser;
    }

    public function getAppUserById(int $id): ?AppUser
    {
        $appUser = new AppUser(
            "toto@toto.to",
            '$2y$10$zJ1xMnkzVB6rqYfxx0Skg.ZNre8Eye1X7t97uW9yRdHZswoIMlBJ.',
            "De Toto",
            "Toto",
            "Toto&Cie",
            "appUser",
            true
        );

        $appUser->setId(1);

        // return null;
        return $appUser;
    }

    public function addAppUser(AppUser $appUser): void
    {

    }

    public function updateAppUser(AppUser $appUser): void
    {

    }

    public function deleteAppUser(string $id): void
    {

    }
}