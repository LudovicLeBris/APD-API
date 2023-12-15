<?php

namespace App\Domain\Apd\UseCase\GetProject;

use App\Domain\Apd\Entity\Project;
use App\Domain\Apd\Entity\ProjectRepositoryInterface;
use App\Domain\Apd\UseCase\GetProject\GetProjectPresenter;

class GetProject
{
    private $projectRepository;

    public function __construct(ProjectRepositoryInterface $projectRepository)
    {
        $this->projectRepository = $projectRepository;
    }

    public function execute(GetProjectRequest $request, GetProjectPresenter $presenter)
    {
        $response = new GetProjectResponse();

        $project = $this->projectRepository->getProjectById($request->id);
        $isValid = $this->checkProjectExist($project, $response);

        if ($isValid) {
            $response->setProject($project);
        }

        $presenter->present($response);
    }

    private function checkProjectExist(?Project $project, GetProjectResponse $response): bool
    {
        if ($project) {
            return true;
        }

        $response->addError('id', 'Project doesn\'t exist with this id.');
        return false;
    }
}