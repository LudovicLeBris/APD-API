<?php

namespace App\Domain\Apd\UseCase\GetAllProjects;

class GetAllProjectsRequest
{
    public $userId;

    public function __construct(int $userId)
    {
        $this->userId = $userId;
    }
}