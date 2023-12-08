<?php

namespace App\Domain\Apd\UseCase\UpdateProject;

interface UpdateProjectPresenter
{
    public function present(UpdateProjectResponse $response): void;
}