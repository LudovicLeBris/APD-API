<?php

namespace App\Domain\Apd\UseCase\GetProject;

interface GetProjectPresenter
{
    public function present(GetProjectResponse $response): void;
}