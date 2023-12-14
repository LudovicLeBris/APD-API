<?php

namespace App\Tests\Domain\Apd\UseCase\AddProject;

use App\Domain\Apd\UseCase\AddProject\AddProjectRequest;

class AddProjectRequestBuilder extends AddProjectRequest
{
    const NAME = 'project 1';

    public static function aProject()
    {
        $request = new static();
        $request->name = self::NAME;

        return $request;
    }

    public function build()
    {
        $request = new AddProjectRequest();
        $request->name = $this->name;

        return $request;
    }

    public function empty()
    {
        $this->name = null;
    }

    public function setName(string $name)
    {
        $this->name = $name;

        return $this;
    }
}