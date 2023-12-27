<?php

namespace App\Domain\Apd\UseCase\RemoveProject;

class RemoveProjectRequest
{
    public $userId;
    public $projectId;

    public function __construct(int $userId, int $projectId)
    {
        $this->userId = $userId;
        $this->projectId = $projectId;
    }
}