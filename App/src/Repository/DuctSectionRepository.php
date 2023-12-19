<?php

namespace App\Repository;

use App\Domain\Apd\Entity\DuctSection;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\DuctSection as DuctSectionEntity;
use App\Domain\Apd\Entity\DuctSectionRepositoryInterface;
use App\Domain\Apd\Factory\DuctSectionFactory;
use App\Entity\DuctNetwork;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<DuctSection>
 *
 * @method DuctSectionEntity|null find($id, $lockMode = null, $lockVersion = null)
 * @method DuctSectionEntity|null findOneBy(array $criteria, array $orderBy = null)
 * @method DuctSectionEntity[]    findAll()
 * @method DuctSectionEntity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DuctSectionRepository extends ServiceEntityRepository implements DuctSectionRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DuctSectionEntity::class);
    }

    public function getDuctSectionById(int $id): ?DuctSection
    {
        $ductSectionEntity = $this->find($id);

        if ($ductSectionEntity === null) {
            return null;
        }

        $ductSectionFactory = new DuctSectionFactory();
        $ductSectionFactory->setSectionTechnicalDatas([
            "air" => $ductSectionEntity->getAir(),
            "shape" => $ductSectionEntity->getShape(),
            "material" => $ductSectionEntity->getMaterial(),
            "flowrate" => $ductSectionEntity->getFlowrate(),
            "length" => $ductSectionEntity->getLength(),
            "singularities" => $ductSectionEntity->getSingularities(),
            "additionalApd" => $ductSectionEntity->getAdditionalApd(),
            "diameter" => $ductSectionEntity->getDiameter(),
            "width" => $ductSectionEntity->getWidth(),
            "height" => $ductSectionEntity->getHeight()
        ]);

        $ductSection = $ductSectionFactory->createDuctSection();
        $ductSection->setId($ductSectionEntity->getId())
            ->setName($ductSectionEntity->getName())
            ->setDuctNetworkId($ductSectionEntity->getDuctNetwork()->getId());
        
        return $ductSection;
    }

    public function getDuctSectionsByDuctNetworkId(int $ductNetworkId): array
    {
        $qb = $this->createQueryBuilder('ds');

        $qb->select('ds');
        $qb->innerJoin('ds.ductNetwork', 'dn');
        $qb->addSelect('dn');
        $qb->andWhere('dn.id = :ductNetworkId');
        $qb->setParameter('ductNetworkId', $ductNetworkId);
        
        $ductSectionEntities = $qb->getQuery()->getResult();

        $ductSections = [];
        foreach ($ductSectionEntities as $ductSectionEntity) {
            $ductSections[] = $this->getDuctSectionById($ductSectionEntity->getId());
        }

        return $ductSections;
    }

    public function addDuctSection(DuctSection $ductSection): void
    {
        $ductSectionEntity = new DuctSectionEntity();
        $ductSectionEntity
            ->setName($ductSection->getName())
            ->setShape($ductSection->getShape())
            ->setMaterial($ductSection->getMaterial())
            ->setFlowrate($ductSection->getFlowrate())
            ->setLength($ductSection->getLength())
            ->setSingularities($ductSection->getSingularities())
            ->setAdditionalApd($ductSection->getAdditionalApd())
            ->setDiameter($ductSection->getDiameter())
            ->setWidth($ductSection->getWidth())
            ->setHeight($ductSection->getHeight())
            ->setEquivDiameter($ductSection->getEquivDiameter())
            ->setDuctSectionsSection($ductSection->getDuctSectionsSection())
            ->setFlowspeed($ductSection->getFlowspeed())
            ->setLineaApd($ductSection->getLinearApd())
            ->setSingularApd($ductSection->getSingularApd())
            ->setTotalApd($ductSection->getTotalApd())
            ->setAir($ductSection->getAir())
        ;
        $ductNetworkEntity = $this->getEntityManager()
            ->getRepository(DuctNetwork::class)
            ->getDuctNetworkEntityById($ductSection->getDuctNetworkId());
        $ductSectionEntity->setDuctNetwork($ductNetworkEntity);

        $this->getEntityManager()->persist($ductSectionEntity);
        $this->getEntityManager()->flush();
    }

    public function updateDuctSection(DuctSection $ductSection): void
    {
        $ductSectionEntity = $this->find($ductSection->getId());
        
        $ductSectionEntity
            ->setName($ductSection->getName())
            ->setShape($ductSection->getShape())
            ->setMaterial($ductSection->getMaterial())
            ->setFlowrate($ductSection->getFlowrate())
            ->setLength($ductSection->getLength())
            ->setSingularities($ductSection->getSingularities())
            ->setAdditionalApd($ductSection->getAdditionalApd())
            ->setDiameter($ductSection->getDiameter())
            ->setWidth($ductSection->getWidth())
            ->setHeight($ductSection->getHeight())
            ->setEquivDiameter($ductSection->getEquivDiameter())
            ->setDuctSectionsSection($ductSection->getDuctSectionsSection())
            ->setFlowspeed($ductSection->getFlowspeed())
            ->setLineaApd($ductSection->getLinearApd())
            ->setSingularApd($ductSection->getSingularApd())
            ->setTotalApd($ductSection->getTotalApd())
            ->setAir($ductSection->getAir())
        ;

        $this->getEntityManager()->persist($ductSectionEntity);
        $this->getEntityManager()->flush();
    }

    public function deleteDucSection(int $id): void
    {
        $ductSectionEntity = $this->find($id);

        $this->getEntityManager()->remove($ductSectionEntity);
        $this->getEntityManager()->flush();
    }

//    /**
//     * @return DuctSection[] Returns an array of DuctSectionEntity objects
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

//    public function findOneBySomeField($value): ?DuctSectionEntity
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
