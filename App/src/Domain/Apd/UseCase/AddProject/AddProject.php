<?php

namespace App\Domain\Apd\UseCase\AddProject;

use App\Domain\Apd\Entity\Project;
use App\Domain\Apd\Entity\ProjectRepositoryInterface;
use Assert\Assert;
use Assert\LazyAssertionException;

class AddProject
{
    private $projectRepository;

    public function __construct(ProjectRepositoryInterface $projectRepository)
    {
        $this->projectRepository = $projectRepository;
    }

    public function execute(AddProjectRequest $request, AddProjectPresenter $presenter)
    {
        $response = new AddProjectResponse();
        $isValid = $this->checkRequest($request, $response);

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
                ->that($request->name, 'name')->notEmpty('Name is empty')->string()
                ->verifyNow();
            
            return true;
        } catch (LazyAssertionException $e) {
            foreach ($e->getErrorExceptions() as $error) {
                $response->addError($error->getPropertyPath(), $error->getMessage());
            }
            return false;
        }
    }

    private function setProject(AddProjectRequest $request): Project
    {
        $project = new Project($request->name);

        return $project;
    }
}