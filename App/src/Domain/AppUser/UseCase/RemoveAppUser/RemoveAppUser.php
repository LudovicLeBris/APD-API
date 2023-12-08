<?php

namespace App\Domain\AppUser\UseCase\RemoveAppUser;

use App\Domain\Apd\Entity\DuctNetworkRepositoryInterface;
use App\Domain\Apd\Entity\DuctSectionRepositoryInterface;
use App\Domain\Apd\Entity\ProjectRepositoryInterface;
use App\Domain\AppUser\Entity\AppUserRepositoryInterface;

class RemoveAppUser
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

    public function execute(RemoveAppUserRequest $request, RemoveAppUserPresenter $presenter)
    {
        $response = new RemoveAppUserResponse();
        $appUserToRemove = $this->appUserRepository->getAppUserById($request->id);
        
        if (is_null($appUserToRemove)) {
            $response->addError('User', 'There is no user with this id');
        } else {
            $projects = $this->projectRepository->getProjectsByUserId($appUserToRemove->getId());
    
            foreach ($projects as $project) {
                foreach ($project->getDuctNetworks() as $ductNetwork) {
    
                    array_map(function($ductSection) 
                    {
                    $this->ductSectionRepository->deleteDucSection($ductSection->getId());
                    }, $ductNetwork->getDuctSections());
                
                    $this->ductNetworkRepository->deleteDucNetwork($ductNetwork->getId());
                }
            $this->projectRepository->deleteProject($project->getId());
            }
    
            $this->appUserRepository->deleteAppUser($appUserToRemove->getId());
    
            $response->setAppUser($appUserToRemove);
        }

        $presenter->present($response);
    }
}