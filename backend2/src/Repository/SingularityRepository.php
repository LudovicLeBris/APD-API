<?php

namespace App\Repository;

use App\Entity\Singularity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Singularity>
 *
 * @method Singularity|null find($id, $lockMode = null, $lockVersion = null)
 * @method Singularity|null findOneBy(array $criteria, array $orderBy = null)
 * @method Singularity[]    findAll()
 * @method Singularity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SingularityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Singularity::class);
    }

//    /**
//     * @return Singularity[] Returns an array of Singularity objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Singularity
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
