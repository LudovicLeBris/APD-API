<?php

namespace App\Domain\Apd\UseCase\RemoveDuctNetwork;

use App\Domain\Apd\Entity\DuctNetworkRepositoryInterface;
use App\Domain\Apd\Entity\DuctSectionRepositoryInterface;
use App\Domain\Apd\Entity\ProjectRepositoryInterface;

class RemoveDuctNetwork
{
    private $projectRepository;
    private $ductNetworkRepository;
    private $ductSectionRepository;

    public function __construct(
        ProjectRepositoryInterface $projectRepository,
        DuctNetworkRepositoryInterface $ductNetworkRepository,
        DuctSectionRepositoryInterface $ductSectionRepository
    )
    {
        $this->projectRepository = $projectRepository;
        $this->ductNetworkRepository = $ductNetworkRepository;
        $this->ductSectionRepository = $ductSectionRepository;
    }

    public function execute(RemoveDuctNetworkRequest $request, RemoveDuctNetworkPresenter $presenter)
    {
        $response = new RemoveDuctNetworkResponse();
        $isValid = $this->checkRequest($request, $response);

        if ($isValid) {
            $project = $this->projectRepository->getProjectById($request->projectId);
            $ductNetworkToRemove = $this->ductNetworkRepository->getDuctNetworkById($request->ductNetworkId);
    
            foreach ($ductNetworkToRemove->getDuctSections() as $ductSection)
            {
                $this->ductSectionRepository->deleteDucSection($ductSection->getId());
            }
    
            $this->ductNetworkRepository->deleteDucNetwork($request->ductNetworkId);
    
            $project->removeDuctNetwork($ductNetworkToRemove);
            $this->projectRepository->updateProject($project);

            $response->setDuctNetwork($ductNetworkToRemove);
        }
        

        $presenter->present($response);
    }

    private function checkRequest(RemoveDuctNetworkRequest $request, RemoveDuctNetworkResponse $response)
    {
        $isValid = true;
        
        $project = $this->projectRepository->getProjectById($request->projectId);
        if (is_null($project)) {
            $response->addError('project', 'There is no project with this id', 404);
            $isValid = $isValid && false;
        }

        $ductNetworkToRemove = $this->ductNetworkRepository->getDuctNetworkById($request->ductNetworkId);
        if (is_null($ductNetworkToRemove)) {
            $response->addError('ductNetwork', 'There is no duct network with this id', 404);
            $isValid = $isValid && false;
        }

        return $isValid;
    }
}