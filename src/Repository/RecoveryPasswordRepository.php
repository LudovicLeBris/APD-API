<?php

namespace App\Repository;

use Doctrine\Persistence\ManagerRegistry;
use App\Domain\AppUser\Entity\RecoveryPassword;
use App\Domain\AppUser\Entity\RecoveryPasswordRepositoryInterface;
use App\Entity\RecoveryPassword as RecoveryPasswordEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<RecoveryPassword>
 *
 * @method RecoveryPasswordEntity|null find($id, $lockMode = null, $lockVersion = null)
 * @method RecoveryPasswordEntity|null findOneBy(array $criteria, array $orderBy = null)
 * @method RecoveryPasswordEntity[]    findAll()
 * @method RecoveryPasswordEntity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RecoveryPasswordRepository extends ServiceEntityRepository implements RecoveryPasswordRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RecoveryPasswordEntity::class);
    }

    public function getRecoveryPasswordByGuid(string $guid): ?RecoveryPassword
    {
        $recoveryPasswordEntity = $this->findOneBy(['guid' => $guid]);

        if ($recoveryPasswordEntity === null) {
            return null;
        }

        $recoveryPassword = new RecoveryPassword(
            $recoveryPasswordEntity->getGuid(),
            $recoveryPasswordEntity->getAppUserId(),
            $recoveryPasswordEntity->getAppUserEmail(),
            $recoveryPasswordEntity->getRequestDateTime(),
            $recoveryPasswordEntity->isIsEnable()
        );

        return $recoveryPassword;
    }

    public function getRecoveryPasswordByAppUserId(int $appUserId): ?RecoveryPassword
    {
        $recoveryPasswordEntity = $this->findOneBy(['guid' => $appUserId]);

        if ($recoveryPasswordEntity === null) {
            return null;
        }

        $recoveryPassword = new RecoveryPassword(
            $recoveryPasswordEntity->getGuid(),
            $recoveryPasswordEntity->getAppUserId(),
            $recoveryPasswordEntity->getAppUserEmail(),
            $recoveryPasswordEntity->getRequestDateTime(),
            $recoveryPasswordEntity->isIsEnable()
        );

        return $recoveryPassword;
    }

    public function addRecoveryPassword(RecoveryPassword $recoveryPassword): void
    {
        $recoveryPasswordEntity = new RecoveryPasswordEntity();
        $recoveryPasswordEntity
            ->setGuid($recoveryPassword->getGuid())
            ->setAppUserId($recoveryPassword->getAppUserId())
            ->setAppUserEmail($recoveryPassword->getAppUserEmail())
            ->setRequestDateTime($recoveryPassword->getRequestDateTime())
            ->setIsEnable($recoveryPassword->getIsEnable())
        ;

        $this->getEntityManager()->persist($recoveryPasswordEntity);
        $this->getEntityManager()->flush($recoveryPasswordEntity);

    }

    public function deleteRecoveryPassword(string $guid): void
    {
        $recoveryPasswordEntity = $this->findOneBy(['guid' => $guid]);
        $this->getEntityManager()->remove($recoveryPasswordEntity);
        $this->getEntityManager()->flush($recoveryPasswordEntity);
    }

//    /**
//     * @return RecoveryPassword[] Returns an array of RecoveryPassword objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?RecoveryPassword
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
