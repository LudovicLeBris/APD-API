<?php

namespace App\Domain\Apd\UseCase\GetProject;

class GetProjectRequest
{
    public $appUserId;
    public $projectId;

    public function __construct(int $appUserId, int $projectId)
    {
        $this->appUserId = $appUserId;
        $this->projectId = $projectId;
    }
}