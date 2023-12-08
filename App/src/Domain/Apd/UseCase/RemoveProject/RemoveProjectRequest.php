<?php

namespace App\Domain\Apd\UseCase\RemoveProject;

class RemoveProjectRequest
{
    public $id;

    public function __construct(int $projectId)
    {
        $this->id = $projectId;
    }
}