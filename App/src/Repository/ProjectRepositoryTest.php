<?php

namespace App\Repository;

use App\Domain\Apd\Entity\DuctNetwork;
use App\Domain\Apd\Entity\Project;
use App\Domain\Apd\Entity\ProjectRepositoryInterface;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

class ProjectRepositoryTest extends ServiceEntityRepository implements ProjectRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DuctNetwork::class);
    }

    public function getProjectById(int $id): ?Project
    {
        $project = new Project('Test');
        $project->setId(1);

        $ductNetwork = new DuctNetwork('test', 'galvanised_steel');
        $ductNetwork->setId(1);
        $project->addDuctNetwork($ductNetwork);

        // return null;
        return $project;
    }

    public function getProjectsByUserId(int $appUserId): array
    {
        $projects = [];

        $project1 = (new Project('test1'))->setId(1)->setUserId(1);
        $project2 = (new Project('test2'))->setId(2)->setUserId(1);

        $ductNetwork = new DuctNetwork('test', 'galvanised_steel');
        $ductNetwork->setId(1);
        $project1->addDuctNetwork($ductNetwork);
        $project2->addDuctNetwork($ductNetwork);

        $projects[] = $project1;
        $projects[] = $project2;

        // return null;
        return $projects;
    }

    public function addProject(Project $project): void
    {

    }

    public function updateProject(Project $project)
    {

    }

    public function deleteProject(int $id): void
    {
        
    }
}