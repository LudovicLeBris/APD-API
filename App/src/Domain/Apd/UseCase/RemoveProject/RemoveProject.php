<?php

namespace App\Domain\Apd\UseCase\RemoveProject;

use App\Domain\Apd\Entity\ProjectRepositoryInterface;
use App\Domain\Apd\Entity\DuctNetworkRepositoryInterface;
use App\Domain\Apd\Entity\DuctSectionRepositoryInterface;

class RemoveProject
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

    public function execute(RemoveProjectRequest $request, RemoveProjectPresenter $presenter)
    {
        $response = new RemoveProjectResponse();
        $projectToRemove = $this->projectRepository->getProjectById($request->id);

        if (is_null($projectToRemove)) {
            $response->addError('project', 'There is no project with this id');
        } else {
            foreach ($projectToRemove->getDuctNetworks() as $ductNetwork) {
    
                array_map(function($ductSection) 
                    {
                    $this->ductSectionRepository->deleteDucSection($ductSection->getId());
                    }, $ductNetwork->getDuctSections());
                
                $this->ductNetworkRepository->deleteDucNetwork($ductNetwork->getId());
            }
    
            $this->projectRepository->deleteProject($request->id);

            $response->setProject($projectToRemove);
        }

        $presenter->present($response);
    }
}