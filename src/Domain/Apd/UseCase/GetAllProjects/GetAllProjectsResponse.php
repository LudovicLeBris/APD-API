<?php

namespace App\Domain\Apd\UseCase\GetAllProjects;

use App\Domain\CoreResponse;

class GetAllProjectsResponse extends CoreResponse
{
    private $allProjects;

    public function __construct() {}

    public function getAllProjects(): ?array
    {
        return $this->allProjects;
    }

    public function setAllProjects(?array $allProjects): static
    {
        $this->allProjects = $allProjects;

        return $this;
    }
}