<?php

namespace App\Domain\Apd\UseCase\GetAllDuctNetworks;

use App\Domain\Apd\Entity\Project;
use App\Domain\Apd\Entity\ProjectRepositoryInterface;
use App\Domain\Apd\Entity\DuctNetworkRepositoryInterface;

class GetAllDuctNetworks
{
    private $projectRepository;
    private $ductNetworkRepository;

    public function __construct(
        ProjectRepositoryInterface $projectRepository,
        DuctNetworkRepositoryInterface $ductNetworkRepository
    )
    {
        $this->projectRepository = $projectRepository;
        $this->ductNetworkRepository = $ductNetworkRepository;
    }

    public function execute(GetAllDuctNetworksRequest $request, GetAllDuctNetworksPresenter $presenter)
    {
        $response = new GetAllDuctNetworksResponse();

        $project = $this->projectRepository->getProjectById($request->projectId);
        $isValid = $this->checkProjectExist($project, $response);

        if ($isValid) {
            $response->setAllDuctNetworks($this->ductNetworkRepository->getDuctNetworksByProjectId($project->getId()));
        }

        $presenter->present($response);
    }

    private function checkProjectExist(?Project $project, GetAllDuctNetworksResponse $response): bool
    {
        if ($project) {
            return true;
        }

        $response->addError('projectId', 'Project doesn\'t exist with this id.', 404);
        return false;
    }
}