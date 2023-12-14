<?php

namespace App\Domain\Apd\UseCase\GetProject;

use App\Domain\Apd\Entity\Project;

class GetProjectResponse
{
    private $project;

    public function getProject(): ?Project
    {
        return $this->project;
    }

    public function setProject(?Project $project): static
    {
        $this->project = $project;

        return $this;
    }
}