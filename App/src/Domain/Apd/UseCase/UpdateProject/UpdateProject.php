<?php

namespace App\Domain\Apd\UseCase\UpdateProject;

use App\Domain\Apd\Entity\DuctNetworkRepositoryInterface;
use App\Domain\Apd\Entity\DuctSectionRepositoryInterface;
use App\Domain\Apd\Entity\Project;
use App\Domain\Apd\Entity\ProjectRepositoryInterface;
use App\Domain\AppUser\Entity\AppUser;
use App\Domain\AppUser\Entity\AppUserRepositoryInterface;
use Assert\Assert;
use Assert\LazyAssertionException;

class UpdateProject
{
    private $appUserRepository;
    private $projectRepository;
    private $ductNetworkRepository;
    private $ductSectionRepository;

    public function __construct(
        AppUserRepositoryInterface $appUserRepository,
        ProjectRepositoryInterface $projectRepository,
        DuctNetworkRepositoryInterface $ductNetworkRepository,
        DuctSectionRepositoryInterface $ductSectionRepository
    )
    {
        $this->appUserRepository = $appUserRepository;
        $this->projectRepository = $projectRepository;
        $this->ductNetworkRepository = $ductNetworkRepository;
        $this->ductSectionRepository = $ductSectionRepository;
    }

    public function execute(UpdateProjectRequest $request, UpdateProjectPresenter $presenter)
    {
        $response = new UpdateProjectResponse();
        $appUser = $this->appUserRepository->getAppUserById($request->userId);
        $oldProject = $this->projectRepository->getProjectById($request->projectId);
        $isValid = $this->checkUserExist($appUser, $response);
        $isValid = $isValid && $this->checkProjectExist($oldProject, $response);
        $isValid = $isValid && $this->checkRequest($request, $response);

        if ($isValid) {
            $updatedProject = $this->updateProject($request, $oldProject);

            $this->projectRepository->updateProject($updatedProject);

            $response->setProject($updatedProject);
        }

        $presenter->present($response);
    }

    private function checkRequest(UpdateProjectRequest $request, UpdateProjectResponse $response)
    {
        try {
            Assert::lazy()
            ->that($request->name, 'name')->satisfy(function($value) {
                if (!is_null($value)) {
                    return is_string($value) && $value !== "";
                }
            }, 'Name must be a string value or not empty string')
            ->that($request->generalAltitude, 'generalAltitude')->satisfy(function($value) {
                if (!is_null($value)) {
                    return is_int($value);
                }
            }, 'GeneralAltitude must be an integer value')
            ->that($request->generalTemperature, 'generalTemperature')->satisfy(function($value) {
                if (!is_null($value)) {
                    return is_float($value);
                }
            }, 'GeneralTemperature must be a float value')
                ->verifyNow();
            
            return true;
        } catch (LazyAssertionException $e) {
            foreach ($e->getErrorExceptions() as $error) {
                $response->addError($error->getPropertyPath(), $error->getMessage());
            }
            return false;
        }
    }

    private function checkUserExist(?AppUser $appUser, UpdateProjectResponse $response)
    {
        if ($appUser) {
            return true;
        }
        $response->addError('userId', 'User doesn\'t exist with this id.');
        return false;
    }

    private function checkProjectExist(?Project $project, UpdateProjectResponse $response)
    {
        if ($project) {
            return true;
        }
        $response->addError('projectId', 'Project doesn\'t exist with this id.');
        return false;
    }

    private function updateProject(UpdateProjectRequest $request, Project $oldProject): Project
    {
        if (is_null($request->name)) {
            $name = $oldProject->getName();
        } else {
            $name = $request->name;
        }

        $project = new Project($name);
        $project->setId($oldProject->getId());
        $project->setUserId($oldProject->getuserId());

        foreach ($oldProject->getDuctNetworks() as $ductNetwork) {
            $project->addDuctNetwork($ductNetwork);
        }

        if (!is_null($request->generalAltitude)) {
            $project->setGeneralAltitude($request->generalAltitude);
        }

        if (!is_null($request->generalTemperature)) {
            $project->setGeneralTemperature($request->generalTemperature);
        }

        foreach ($project->getDuctNetworks() as $ductNetwork) {
            $this->ductNetworkRepository->updateDuctNetwork($ductNetwork);
            foreach ($ductNetwork->getDuctSections() as $ductSection) {
                $this->ductSectionRepository->updateDuctSection($ductSection);
            }
        }

        return $project;
    }
}