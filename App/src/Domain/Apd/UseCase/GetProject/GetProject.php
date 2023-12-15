<?php

namespace App\Domain\Apd\UseCase\GetProject;

use App\Domain\Apd\Entity\Project;
use App\Domain\Apd\Entity\ProjectRepositoryInterface;
use App\Domain\Apd\UseCase\GetProject\GetProjectPresenter;
use App\Domain\AppUser\Entity\AppUser;
use App\Domain\AppUser\Entity\AppUserRepositoryInterface;

class GetProject
{
    private $appUserRepository;
    private $projectRepository;

    public function __construct(
        AppUserRepositoryInterface $appUserRepository,
        ProjectRepositoryInterface $projectRepository
    )
    {
        $this->appUserRepository = $appUserRepository;
        $this->projectRepository = $projectRepository;
    }

    public function execute(GetProjectRequest $request, GetProjectPresenter $presenter)
    {
        $response = new GetProjectResponse();

        $appUser = $this->appUserRepository->getAppUserById($request->appUserId);
        $project = $this->projectRepository->getProjectById($request->projectId);
        $isValid = $this->checkUserExist($appUser, $response);
        $isValid = $isValid && $this->checkProjectExist($project, $response);

        if ($isValid) {
            $response->setProject($project);
        }

        $presenter->present($response);
    }

    private function checkUserExist(?AppUser $appUser, GetProjectResponse $response): bool
    {
        if ($appUser) {
            return true;
        }

        $response->addError('userId', 'User doesn\'t exist with this id.');
        return false;
    }

    private function checkProjectExist(?Project $project, GetProjectResponse $response): bool
    {
        if ($project) {
            return true;
        }

        $response->addError('projectId', 'Project doesn\'t exist with this id.');
        return false;
    }
}