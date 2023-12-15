<?php

namespace App\Tests\_Mock\Domain\Apd\Entity;

use App\Domain\Apd\Entity\Project;
use App\Domain\Apd\Entity\ProjectRepositoryInterface;

class InMemoryProjectRepository implements ProjectRepositoryInterface
{
    private $projects = [];
    
    public function getProjectById(int $id): ?Project
    {
        $find = function (Project $project) use ($id) {
            return $project->getId() === $id;
        };

        $projectsFound = array_values(array_filter($this->projects, $find));
        if(count($projectsFound) === 1) {
            return $projectsFound[0];
        }
        
        return null;
    }

    public function getProjectsByUserId(int $appUserId): array
    {
        $find = function (Project $project) use ($appUserId) {
            return $project->getuserId() === $appUserId;
        };

        $projectsFound = array_values(array_filter($this->projects, $find));

        return $projectsFound;
    }

    public function addProject(Project $project): void
    {
        if (!isset($project->id)) {
            $project->setId(mt_rand(0, 500));
        }
        
        $this->projects[] = $project;
    }

    public function updateProject(Project $project)
    {
        for ($i=0; $i < count($this->projects); $i++) {
            if ($this->projects[$i]->getId() === $project->getId()) {
                $this->projects[$i] = $project;
                break;
            }
        }
    }

    public function deleteProject(int $id): void
    {
        for ($i=0; $i < count($this->projects); $i++) {
            if ($this->projects[$i]->getId() === $id) {
                array_splice($this->projects, $i, 1);
                break;
            }
        }
    }
}