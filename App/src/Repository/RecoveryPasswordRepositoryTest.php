<?php

namespace App\Repository;

use App\Domain\AppUser\Entity\RecoveryPassword;
use Doctrine\Persistence\ManagerRegistry;
use App\Domain\AppUser\Entity\RecoveryPasswordRepositoryInterface;
use App\SharedKernel\Service\TokenGenerator;
use DateTimeImmutable;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

class RecoveryPasswordRepositoryTest extends ServiceEntityRepository implements RecoveryPasswordRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RecoveryPassword::class);
    }

    public function getRecoveryPasswordByGuid(string $guid): ?RecoveryPassword
    {
        $recoveryPassword = new RecoveryPassword(
            (new TokenGenerator())->getToken(),
            1,
            "toto@gmail.to",
            new DateTimeImmutable('2023-12-08 09:05:00')
        );

        // return null;
        return $recoveryPassword;
    }

    public function getRecoveryPasswordByAppUserId(int $AppUserId): ?RecoveryPassword
    {
        $recoveryPassword = new RecoveryPassword(
            (new TokenGenerator())->getToken(),
            1,
            "toto@gmail.to",
            new DateTimeImmutable('2023-12-06 13:13:00')
        );

        return $recoveryPassword;
    }

    public function addRecoveryPassword(RecoveryPassword $recoveryPassword): void
    {

    }

    public function deleteRecoveryPassword(string $guid): void
    {

    }
}