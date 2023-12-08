<?php

namespace App\Domain\Apd\UseCase\UpdateProject;

use App\Domain\Apd\Entity\Project;
use App\Domain\Apd\Entity\ProjectRepositoryInterface;
use Assert\Assert;
use Assert\LazyAssertionException;

class UpdateProject
{
    private $projectRepository;

    public function __construct(
        ProjectRepositoryInterface $projectRepository
    )
    {
        $this->projectRepository = $projectRepository;
    }

    public function execute(UpdateProjectRequest $request, UpdateProjectPresenter $presenter)
    {
        $response = new UpdateProjectResponse();
        $isValid = $this->checkRequest($request, $response);

        if ($isValid) {
            $oldProject = $this->projectRepository->getProjectById($request->id);
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

    private function updateProject(UpdateProjectRequest $request, Project $oldProject): Project
    {
        if (is_null($request->name)) {
            $name = $oldProject->getName();
        } else {
            $name = $request->name;
        }

        $project = new Project($name);

        $project->setId($oldProject->getId());

        foreach ($oldProject->getDuctNetworks() as $ductNetwork) {
            $project->addDuctNetwork($ductNetwork);
        }

        if (!is_null($request->generalAltitude)) {
            $project->setGeneralAltitude($request->generalAltitude);
        }

        if (!is_null($request->generalTemperature)) {
            $project->setGeneralTemperature($request->generalTemperature);
        }

        return $project;
    }
}