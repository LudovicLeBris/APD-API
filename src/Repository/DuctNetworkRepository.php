<?php

namespace App\Repository;

use App\Domain\Apd\Entity\DuctNetwork;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\DuctNetwork as DuctNetworkEntity;
use App\Domain\Apd\Entity\DuctNetworkRepositoryInterface;
use App\Entity\DuctSection as DuctSectionEntity;
use App\Entity\Project;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<DuctNetwork>
 *
 * @method DuctNetworkEntity|null find($id, $lockMode = null, $lockVersion = null)
 * @method DuctNetworkEntity|null findOneBy(array $criteria, array $orderBy = null)
 * @method DuctNetworkEntity[]    findAll()
 * @method DuctNetworkEntity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DuctNetworkRepository extends ServiceEntityRepository implements DuctNetworkRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DuctNetworkEntity::class);
    }

    public function getDuctNetworkById(int $id): ?DuctNetwork
    {
        $ductNetworkEntity = $this->find($id);

        if ($ductNetworkEntity === null) {
            return null;
        }

        $ductNetwork = new DuctNetwork(
            $ductNetworkEntity->getName(),
            $ductNetworkEntity->getGeneralMaterial(),
            $ductNetworkEntity->getAdditionalApd()
        );
        $ductNetwork
            ->setId($ductNetworkEntity->getId())
            ->setProjectId($ductNetworkEntity->getProject()->getId())
            ->setAir($ductNetworkEntity->getAir())
            ->setAltitude($ductNetworkEntity->getAltitude())
            ->setTemperature($ductNetworkEntity->getTemperature())
        ;
        $ductSections = $this->getEntityManager()
            ->getRepository(DuctSectionEntity::class)
            ->getDuctSectionsByDuctNetworkId($ductNetwork->getId());
        foreach ($ductSections as $ductSection) {
            $ductNetwork->addDuctSection($ductSection);
        }

        return $ductNetwork;
    }

    public function getDuctNetworkEntityById(int $id): ?DuctNetworkEntity
    {
        $ductNetworkEntity = $this->find($id);

        if ($ductNetworkEntity === null) {
            return null;
        }

        return $ductNetworkEntity;
    }

    public function getDuctNetworksByProjectId(int $projectId): array
    {
        $qb = $this->createQueryBuilder('dn');

        $qb->select('dn');
        $qb->innerJoin('dn.project', 'p');
        $qb->addSelect('p');
        $qb->andWhere('p.id = :projectId');
        $qb->setParameter('projectId', $projectId);

        $ductNetworkEntities = $qb->getQuery()->getResult();

        $ductNetworks = [];
        foreach ($ductNetworkEntities as $ductNetworkEntity) {
            $ductNetworks[] = $this->getDuctNetworkById($ductNetworkEntity->getId());
        }

        return $ductNetworks;
    }

    public function addDuctNetwork(DuctNetwork $ductNetwork): void
    {
        $ductNetworkEntity = new DuctNetworkEntity();
        $ductNetworkEntity
            ->setName($ductNetwork->getName())
            ->setAltitude($ductNetwork->getAltitude())
            ->setTemperature($ductNetwork->getTemperature())
            ->setGeneralMaterial($ductNetwork->getGeneralMaterial())
            ->setAdditionalApd($ductNetwork->getAdditionalApd())
            ->setTotalLinearApd($ductNetwork->getTotalLinearApd())
            ->setTotalSingularApd($ductNetwork->getTotalSingularApd())
            ->setTotalAdditionalApd($ductNetwork->getTotalAdditionalApd())
            ->setTotalApd($ductNetwork->getTotalApd())
            ->setAir($ductNetwork->getAir())
        ;
        $projectEntity = $this->getEntityManager()
            ->getRepository(Project::class)
            ->getProjectEntityById($ductNetwork->getProjectId());
        $ductNetworkEntity->setProject($projectEntity);

        $this->getEntityManager()->persist($ductNetworkEntity);
        $this->getEntityManager()->flush();
    }

    public function updateDuctNetwork(DuctNetwork $ductNetwork): void
    {
        $ductNetworkEntity = $this->find($ductNetwork->getId());
        $ductNetworkEntity
            ->setName($ductNetwork->getName())
            ->setAltitude($ductNetwork->getAltitude())
            ->setTemperature($ductNetwork->getTemperature())
            ->setGeneralMaterial($ductNetwork->getGeneralMaterial())
            ->setAdditionalApd($ductNetwork->getAdditionalApd())
            ->setTotalLinearApd($ductNetwork->getTotalLinearApd())
            ->setTotalSingularApd($ductNetwork->getTotalSingularApd())
            ->setTotalAdditionalApd($ductNetwork->getTotalAdditionalApd())
            ->setTotalApd($ductNetwork->getTotalApd())
            ->setAir($ductNetwork->getAir())
        ;
        // foreach ($ductNetwork->getDuctSections() as $ductSection) {
        //     $ductSectionEntity = new DuctSectionEntity();
        //     $ductSectionEntity
        //         ->setId($ductSection->getId())
        //         ->setName($ductSection->getName())
        //         ->setShape($ductSection->getShape())
        //         ->setMaterial($ductSection->getMaterial())
        //         ->setFlowrate($ductSection->getFlowrate())
        //         ->setLength($ductSection->getLength())
        //         ->setSingularities($ductSection->getSingularities())
        //         ->setAdditionalApd($ductSection->getAdditionalApd())
        //         ->setDiameter($ductSection->getDiameter())
        //         ->setWidth($ductSection->getWidth())
        //         ->setHeight($ductSection->getHeight())
        //         ->setEquivDiameter($ductSection->getEquivDiameter())
        //         ->setDuctSectionsSection($ductSection->getDuctSectionsSection())
        //         ->setFlowspeed($ductSection->getFlowspeed())
        //         ->setLineaApd($ductSection->getLinearApd())
        //         ->setSingularApd($ductSection->getSingularApd())
        //         ->setTotalApd($ductSection->getTotalApd())
        //         ->setAir($ductSection->getAir())
        //     ;
        //     $ductNetworkEntity->addDuctSection($ductSectionEntity);
        // }

        $this->getEntityManager()->persist($ductNetworkEntity);
        $this->getEntityManager()->flush();
    }

    public function deleteDucNetwork(int $id): void
    {
        $ductNetworkEntity = $this->find($id);
        $this->getEntityManager()->remove($ductNetworkEntity);
        $this->getEntityManager()->flush();
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
