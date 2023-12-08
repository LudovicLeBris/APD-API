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
        parent::__construct($registry, DuctNetwork::class);
    }

    public function getAppUserByEmail(string $email): ?AppUser
    {
        $appUser = new AppUser(
            1,
            "toto@gmail.to",
            '$2y$10$XR7CqsobK7a2w16y3zUDm.GEhfAUX4XjLgO4xJn.0l9WPItsiFX2K',
            "De Toto",
            "Toto",
            "Toto&Cie",
            "appUser",
            true
        );

        // return null;
        return $appUser;
    }

    public function getAppUserById(int $id): ?AppUser
    {
        $appUser = new AppUser(
            1,
            "toto@gmail.to",
            '$2y$10$XR7CqsobK7a2w16y3zUDm.GEhfAUX4XjLgO4xJn.0l9WPItsiFX2K',
            "De Toto",
            "Toto",
            "Toto&Cie",
            "appUser",
            true
        );

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