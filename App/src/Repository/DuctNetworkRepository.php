<?php

namespace App\Repository;

use App\Entity\DuctNetwork;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DuctNetwork>
 *
 * @method DuctNetwork|null find($id, $lockMode = null, $lockVersion = null)
 * @method DuctNetwork|null findOneBy(array $criteria, array $orderBy = null)
 * @method DuctNetwork[]    findAll()
 * @method DuctNetwork[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DuctNetworkRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DuctNetwork::class);
    }

//    /**
//     * @return DuctNetwork[] Returns an array of DuctNetwork objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('d.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?DuctNetwork
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
