<?php

namespace App\Domain\Apd\Entity;

interface ProjectRepositoryInterface
{
    public function getProjectById(int $id): ?Project;

    public function getProjectsByUserId(int $appUserId): array;

    public function addProject(Project $project): void;

    public function updateProject(Project $project);

    public function deleteProject(int $id): void;
}