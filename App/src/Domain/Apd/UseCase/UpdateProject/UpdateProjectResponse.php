<?php

namespace App\Domain\Apd\UseCase\UpdateProject;

use App\Domain\Apd\Entity\Project;
use App\Domain\CoreResponse;

class UpdateProjectResponse extends CoreResponse
{
    private $project;

    public function __construct() {}

    public function getProject(): ?Project
    {
        return $this->project;
    }

    public function setProject(Project $project): static
    {
        $this->project = $project;

        return $this;
    }
}