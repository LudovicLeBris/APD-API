<?php

namespace App\Domain\Apd\UseCase\RemoveProject;

use App\Domain\Apd\Entity\ProjectRepositoryInterface;
use App\Domain\Apd\Entity\DuctNetworkRepositoryInterface;
use App\Domain\Apd\Entity\DuctSectionRepositoryInterface;
use App\Domain\Apd\Entity\Project;
use App\Domain\AppUser\Entity\AppUser;
use App\Domain\AppUser\Entity\AppUserRepositoryInterface;

class RemoveProject
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

    public function execute(RemoveProjectRequest $request, RemoveProjectPresenter $presenter)
    {
        $response = new RemoveProjectResponse();
        $appUser = $this->appUserRepository->getAppUserById($request->userId);
        $projectToRemove = $this->projectRepository->getProjectById($request->projectId);
        $isValid = $this->checkUserExist($appUser, $response);
        $isValid = $isValid && $this->checkProjectExist($projectToRemove, $response);

        if ($isValid) {
            foreach ($projectToRemove->getDuctNetworks() as $ductNetwork) {
    
                array_map(function($ductSection) 
                    {
                    $this->ductSectionRepository->deleteDucSection($ductSection->getId());
                    }, $ductNetwork->getDuctSections());
                
                $this->ductNetworkRepository->deleteDucNetwork($ductNetwork->getId());
            }
    
            $this->projectRepository->deleteProject($request->projectId);

            $response->setProject($projectToRemove);
        }

        $presenter->present($response);
    }

    private function checkUserExist(?AppUser $appUser, RemoveProjectResponse $response): bool
    {
        if ($appUser) {
            return true;
        }

        $response->addError('userId', 'User doesn\'t exist with this id.');
        return false;
    }

    private function checkProjectExist(?Project $project, RemoveProjectResponse $response): bool
    {
        if ($project) {
            return true;
        }

        $response->addError('projectId', 'Project doesn\'t exist with this id.');
        return false;
    }
}