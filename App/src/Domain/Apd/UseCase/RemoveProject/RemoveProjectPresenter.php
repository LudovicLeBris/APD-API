<?php

namespace App\Domain\Apd\UseCase\RemoveProject;

interface RemoveProjectPresenter
{
    public function present(RemoveProjectResponse $response): void;
}