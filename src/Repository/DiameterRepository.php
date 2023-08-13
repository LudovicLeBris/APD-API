<?php

namespace App\Repository;

use App\Entity\Diameter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Diameter>
 *
 * @method Diameter|null find($id, $lockMode = null, $lockVersion = null)
 * @method Diameter|null findOneBy(array $criteria, array $orderBy = null)
 * @method Diameter[]    findAll()
 * @method Diameter[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DiameterRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Diameter::class);
    }
    
    public function findOneByDiameter($diameter): ?Diameter
    {
        return $this->createQueryBuilder('d')
        ->where('d.diameter >= :diameter')
        ->setParameter('diameter', $diameter)
        ->getQuery()
        ->setMaxResults(1)
        ->getOneOrNullResult()
        ;
    }
    
    //    /**
    //     * @return Diameter[] Returns an array of Diameter objects
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
}
