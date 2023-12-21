<?php

namespace App\Domain\Apd\UseCase\AddProject;

use App\Domain\Apd\Entity\Project;
use App\Domain\Apd\Entity\ProjectRepositoryInterface;
use App\Domain\AppUser\Entity\AppUser;
use App\Domain\AppUser\Entity\AppUserRepositoryInterface;
use Assert\Assert;
use Assert\LazyAssertionException;

class AddProject
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

    public function execute(AddProjectRequest $request, AddProjectPresenter $presenter)
    {
        $response = new AddProjectResponse();

        $appUser = $this->appUserRepository->getAppUserById($request->userId);
        $isValid = $this->checkUserExist($appUser, $response);
        $isValid = $isValid && $this->checkRequest($request, $response);

        if ($isValid) {
            $project = $this->setProject($request);

            $this->projectRepository->addProject($project);

            $response->setProject($project);
        }

        $presenter->present($response);
    }

    private function checkRequest(AddProjectRequest $request, AddProjectResponse $response)
    {
        try {
            Assert::lazy()
                ->that($request->name, 'name')->notEmpty('Name is empty.')->string()
                ->verifyNow();
            
            return true;
        } catch (LazyAssertionException $e) {
            foreach ($e->getErrorExceptions() as $error) {
                $response->addError($error->getPropertyPath(), $error->getMessage(), 422);
            }
            return false;
        }
    }

    private function checkUserExist(?AppUser $appUser, AddProjectResponse $response): bool
    {
        if ($appUser) {
            return true;
        }

        $response->addError('userId', 'User doesn\'t exist with this id.', 404);
        return false;
    }

    private function setProject(AddProjectRequest $request): Project
    {
        $project = new Project($request->name);
        $project->setUserId($request->userId);

        return $project;
    }
}