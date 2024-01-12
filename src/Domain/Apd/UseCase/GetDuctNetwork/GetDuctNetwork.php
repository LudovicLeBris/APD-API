<?php

namespace App\Domain\Apd\UseCase\GetDuctNetwork;

use App\Domain\Apd\Entity\DuctNetwork;
use App\Domain\Apd\Entity\Project;
use App\Domain\Apd\Entity\ProjectRepositoryInterface;
use App\Domain\Apd\Entity\DuctNetworkRepositoryInterface;

class GetDuctNetwork
{
    private $projectRepository;
    private $ductNetworkRepository;

    public function __construct(
        ProjectRepositoryInterface $projectRepository,
        DuctNetworkRepositoryInterface $ductNetworkRepository)
    {
        $this->projectRepository = $projectRepository;
        $this->ductNetworkRepository = $ductNetworkRepository;
    }

    public function execute(GetDuctNetworkRequest $request, GetDuctNetworkPresenter $presenter)
    {
        $response = new GetDuctNetworkResponse();

        $project = $this->projectRepository->getProjectById($request->projectId);
        $ductNetwork = $this->ductNetworkRepository->getDuctNetworkById($request->ductNetworkId);
        $isValid = $this->checkProjectExist($project, $response);
        $isValid = $isValid && $this->checkDuctNetworkExist($ductNetwork, $response);
        $isValid = $isValid && $this->checkIfDuctNetworkExistInProject($project, $request, $response);

        if ($isValid) {
            $response->setDuctNetwork($ductNetwork);
        }

        $presenter->present($response);
    }

    private function checkProjectExist(?Project $project, GetDuctNetworkResponse $response): bool
    {
        if ($project) {
            return true;
        }

        $response->addError('projectId', 'Project doesn\'t exist with this id.', 404);
        return false;
    }

    private function checkDuctNetworkExist(?DuctNetwork $ductNetwork, GetDuctNetworkResponse $response): bool
    {
        if ($ductNetwork) {
            return true;
        }

        $response->addError('ductNetworkId', 'Duct network doesn\'t exist with this id.', 404);
        return false;
    }

    private function checkIfDuctNetworkExistInProject(?Project $project, GetDuctNetworkRequest $request, GetDuctNetworkResponse $response): bool
    {
        foreach ($project->getDuctNetworks() as $ductNetwork) {
            if ($ductNetwork->getId() === $request->ductNetworkId) {
                return true;
            }
        }

        $response->addError('ductNetworkId', 'Duct network don\'t belong to this project.', 404);
        return false;
    }
}