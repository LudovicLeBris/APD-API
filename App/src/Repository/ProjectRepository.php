<?php

namespace App\Repository;

use App\Domain\Apd\Entity\DuctNetwork;
use App\Entity\User;
use App\Domain\Apd\Entity\Project;
use App\Entity\Project as ProjectEntity;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\DuctNetwork as DuctNetworkEntity;
use App\Domain\Apd\Entity\ProjectRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Project>
 *
 * @method ProjectEntity|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProjectEntity|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProjectEntity[]    findAll()
 * @method ProjectEntity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProjectRepository extends ServiceEntityRepository implements ProjectRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProjectEntity::class);
    }

    public function getProjectById(int $id): ?Project
    {
        $projectEntity = $this->find($id);

        if ($projectEntity === null) {
            return null;
        }

        $project = new Project($projectEntity->getName());
        $project
            ->setId($projectEntity->getId())
            ->setUserId($projectEntity->getUser()->getId())
            ->setGeneralAltitude($projectEntity->getGeneralAltitude())
            ->setGeneralTemperature($projectEntity->getGeneralTemperature())
        ;
        $ductNetworks = $this->getEntityManager()
            ->getRepository(DuctNetworkEntity::class)
            ->getDuctNetworksByProjectId($project->getId());
        foreach ($ductNetworks as $ductNetwork) {
            $project->addDuctNetwork($ductNetwork);
        }

        return $project;
    }

    public function getProjectEntityById(int $id): ?ProjectEntity
    {
        $projectEntity = $this->find($id);

        if ($projectEntity === null) {
            return null;
        }

        return $projectEntity;
    }

    public function getProjectsByUserId(int $appUserId): array
    {
        $qb = $this->createQueryBuilder('p');

        $qb->select('p');
        $qb->innerJoin('p.user', 'u');
        $qb->addSelect('u');
        $qb->andWhere('u.id = :appUserId');
        $qb->setParameter('appUserId', $appUserId);

        $projectEntities = $qb->getQuery()->getResult();

        $projects = [];
        foreach ($projectEntities as $projectEntity) {
            $projects[] = $this->getProjectById($projectEntity->getId());
        }

        return $projects;
    }

    public function addProject(Project $project): void
    {
        $projectEntity = new ProjectEntity();
        $projectEntity
            ->setName($project->getName())
            ->setGeneralAltitude($project->getGeneralAltitude())
            ->setGeneralTemperature($project->getGeneralTemperature())
        ;
        $user = $this->getEntityManager()->getRepository(User::class)->getUserById($project->getuserId());
        $projectEntity->setUser($user);

        $this->getEntityManager()->persist($projectEntity);
        $this->getEntityManager()->flush();
    }

    public function updateProject(Project $project): void
    {
        $projectEntity = $this->find($project->getId());
        $projectEntity
            ->setName($project->getName())
            ->setGeneralAltitude($project->getGeneralAltitude())
            ->setGeneralTemperature($project->getGeneralTemperature())
        ;
        $user = $this->getEntityManager()->getRepository(User::class)->getUserById($project->getuserId());
        $projectEntity->setUser($user);

        // foreach ($project->getDuctNetworks() as $ductNetwork) {
        //     $ductNetworkEntity = new DuctNetworkEntity();
        //     $ductNetworkEntity
        //         ->setName($ductNetwork->getName())
        //         ->setProject($projectEntity)
        //         ->setAltitude($ductNetwork->getAltitude())
        //         ->setTemperature($ductNetwork->getTemperature())
        //         ->setGeneralMaterial($ductNetwork->getGeneralMaterial())
        //         ->setAdditionalApd($ductNetwork->getAdditionalApd())
        //         ->setTotalLinearApd($ductNetwork->getTotalLinearApd())
        //         ->setTotalSingularApd($ductNetwork->getTotalSingularApd())
        //         ->setTotalAdditionalApd($ductNetwork->getTotalAdditionalApd())
        //         ->setTotalApd($ductNetwork->getTotalApd())
        //         ->setAir($ductNetwork->getAir())
        //     ;
        //     $projectEntity->addDuctNetwork($ductNetworkEntity);
        // }

        $this->getEntityManager()->persist($projectEntity);
        $this->getEntityManager()->flush();
    }

    public function deleteProject(int $id): void
    {
        $projectEntity = $this->find($id);
        $this->getEntityManager()->remove($projectEntity);
        $this->getEntityManager()->flush();
    }

//    /**
//     * @return Project[] Returns an array of Project objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Project
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
