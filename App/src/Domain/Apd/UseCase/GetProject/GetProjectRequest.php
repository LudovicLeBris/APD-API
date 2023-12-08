<?php

namespace App\Domain\Apd\UseCase\GetProject;

class GetProjectRequest
{
    public $id;

    public function __construct($id)
    {
        $this->id = $id;
    }
}