<?php

namespace App\Domain\Apd\UseCase\AddProject;

interface AddProjectPresenter
{
    public function present(AddProjectResponse $response): void;
}