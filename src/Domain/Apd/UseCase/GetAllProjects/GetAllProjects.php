<?php

namespace App\Domain\Apd\UseCase\GetAllProjects;

use App\Domain\Apd\Entity\ProjectRepositoryInterface;
use App\Domain\AppUser\Entity\AppUser;
use App\Domain\AppUser\Entity\AppUserRepositoryInterface;

class GetAllProjects
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

    public function execute(GetAllProjectsRequest $request, GetAllProjectsPresenter $presenter)
    {
        $response = new GetAllProjectsResponse();

        $appUser = $this->appUserRepository->getAppUserById($request->userId);
        $isValid = $this->checkAppUserExist($appUser, $response);

        if ($isValid) {
            $response->setAllProjects($this->projectRepository->getProjectsByUserId($appUser->getId()));
        }

        $presenter->present($response);
    }

    private function checkAppUserExist(?AppUser $appUser, GetAllProjectsResponse $response): bool
    {
        if ($appUser) {
            return true;
        }

        $response->addError('userId', 'User doesn\'t exist with this id.', 404);
        return false;
    }
}