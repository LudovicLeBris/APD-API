<?php

namespace App\Repository;

use App\Entity\DuctSection;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DuctSection>
 *
 * @method DuctSection|null find($id, $lockMode = null, $lockVersion = null)
 * @method DuctSection|null findOneBy(array $criteria, array $orderBy = null)
 * @method DuctSection[]    findAll()
 * @method DuctSection[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DuctSectionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DuctSection::class);
    }

//    /**
//     * @return DuctSection[] Returns an array of DuctSection objects
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

//    public function findOneBySomeField($value): ?DuctSection
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
