<?php

namespace App\Domain\Apd\UseCase\GetAllProjects;

interface GetAllProjectsPresenter
{
    public function present(GetAllProjectsResponse $response): void;
}