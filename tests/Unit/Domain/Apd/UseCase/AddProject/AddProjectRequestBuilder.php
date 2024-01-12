<?php

namespace App\Tests\Domain\Apd\UseCase\AddProject;

use App\Domain\Apd\UseCase\AddProject\AddProjectRequest;

class AddProjectRequestBuilder extends AddProjectRequest
{
    const APPUSER_ID = 1;
    const NAME = 'project 1';

    public static function aProject()
    {
        $request = new static(self::APPUSER_ID);
        $request->name = self::NAME;

        return $request;
    }

    public function build()
    {
        $request = new AddProjectRequest($this->userId);
        $request->name = $this->name;

        return $request;
    }

    public function empty()
    {
        $this->name = null;
    }

    public function setUserId(int $userId)
    {
        $this->userId = $userId;

        return $this;
    }
    
    public function setName(string $name)
    {
        $this->name = $name;

        return $this;
    }
}