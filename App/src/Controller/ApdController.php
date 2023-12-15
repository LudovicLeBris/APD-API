<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

use App\Domain\Apd\UseCase\GetAllDuctSections\GetAllDuctSections;
use App\Domain\Apd\UseCase\GetAllDuctSections\GetAllDuctSectionsRequest;
use App\Presentation\Apd\GetAllDuctSectionsJsonPresenter;
use App\Domain\Apd\UseCase\GetDuctSection\GetDuctSection;
use App\Domain\Apd\UseCase\GetDuctSection\GetDuctSectionRequest;
use App\Presentation\Apd\GetDuctSectionJsonPresenter;
use App\Domain\Apd\UseCase\AddDuctSection\AddDuctSection;
use App\Domain\Apd\UseCase\AddDuctSection\AddDuctSectionRequest;
use App\Presentation\Apd\AddDuctSectionJsonPresenter;
use App\Domain\Apd\UseCase\UpdateDuctSection\UpdateDuctSection;
use App\Domain\Apd\UseCase\UpdateDuctSection\UpdateDuctSectionRequest;
use App\Presentation\Apd\UpdateDuctSectionJsonPresenter;
use App\Domain\Apd\UseCase\RemoveDuctSection\RemoveDuctSection;
use App\Domain\Apd\UseCase\RemoveDuctSection\RemoveDuctSectionRequest;
use App\Presentation\Apd\RemoveDuctSectionJsonPresenter;

use App\Domain\Apd\UseCase\GetAllDuctNetworks\GetAllDuctNetworks;
use App\Domain\Apd\UseCase\GetAllDuctNetworks\GetAllDuctNetworksRequest;
use App\Presentation\Apd\GetAllDuctNetworksJsonPresenter;
use App\Domain\Apd\UseCase\GetDuctNetwork\GetDuctNetwork;
use App\Domain\Apd\UseCase\GetDuctNetwork\GetDuctNetworkRequest;
use App\Presentation\Apd\GetDuctNetworkJsonPresenter;
use App\Domain\Apd\UseCase\AddDuctNetwork\AddDuctNetwork;
use App\Domain\Apd\UseCase\AddDuctNetwork\AddDuctNetworkRequest;
use App\Presentation\Apd\AddDuctNetworkJsonPresenter;
use App\Domain\Apd\UseCase\UpdateDuctNetwork\UpdateDuctNetwork;
use App\Domain\Apd\UseCase\UpdateDuctNetwork\UpdateDuctNetworkRequest;
use App\Presentation\Apd\UpdateDuctNetworkJsonPresenter;
use App\Domain\Apd\UseCase\RemoveDuctNetwork\RemoveDuctNetwork;
use App\Domain\Apd\UseCase\RemoveDuctNetwork\RemoveDuctNetworkRequest;
use App\Presentation\Apd\RemoveDuctNetworkJsonPresenter;

use App\Domain\Apd\UseCase\GetAllProjects\GetAllProjects;
use App\Domain\Apd\UseCase\GetAllProjects\GetAllProjectsRequest;
use App\Presentation\Apd\GetAllProjectsJsonPresenter;
use App\Domain\Apd\UseCase\GetProject\GetProject;
use App\Domain\Apd\UseCase\GetProject\GetProjectRequest;
use App\Presentation\Apd\GetProjectJsonPresenter;
use App\Domain\Apd\UseCase\AddProject\AddProject;
use App\Domain\Apd\UseCase\AddProject\AddProjectRequest;
use App\Presentation\Apd\AddProjectJsonPresenter;
use App\Domain\Apd\UseCase\UpdateProject\UpdateProject;
use App\Domain\Apd\UseCase\UpdateProject\UpdateProjectRequest;
use App\Presentation\Apd\UpdateProjectJsonPresenter;
use App\Domain\Apd\UseCase\RemoveProject\RemoveProject;
use App\Domain\Apd\UseCase\RemoveProject\RemoveProjectRequest;
use App\Presentation\Apd\RemoveProjectJsonPresenter;

#[Route('/api/V1/apd')]
class ApdController extends AbstractController
{
    #[Route(
        '/ductnetworks/{id}/ductsections',
        name: 'app_apd_getductsections',
        methods: ['GET'],
        requirements: ['id' => '\d+']
    )]
    public function getDuctSections(
        int $id,
        GetAllDuctSections $getAllDuctSections,
        GetAllDuctSectionsJsonPresenter $presenter
    )
    {
        $getAllDuctSections->execute(new GetAllDuctSectionsRequest($id), $presenter);

        return $this->json(...$presenter->getJson());
    }
    
    #[Route(
        '/ductnetworks/{ductNetworkId}/ductsections/{ductSectionId}',
        name: 'app_apd_getductsection',
        methods: ['GET'],
        requirements: ['ductNetworkId' => '\d+', 'ductSectionId' => '\d+']
    )]
    public function getDuctSection(
        int $ductNetworkId,
        int $ductSectionId,
        GetDuctSection $getDuctSection,
        GetDuctSectionJsonPresenter $presenter
    )
    {
        $getDuctSection->execute(new GetDuctSectionRequest($ductNetworkId, $ductSectionId), $presenter);

        return $this->json(...$presenter->getJson());

    }

    #[Route(
        '/ductnetworks/{ductNetworkId}/ductsections',
        name: 'app_apd_addductSection',
        methods: ['POST'],
        requirements: ['ductNetworkId' => '\d+']
    )]
    public function addDuctSection(
        int $ductNetworkId,
        Request $request,
        AddDuctSection $addDuctSection,
        AddDuctSectionJsonPresenter $presenter
    ): JsonResponse
    {
        $content = json_decode($request->getContent(), true);

        $nullableRequest = new AddDuctSectionRequest($ductNetworkId);

        $addDuctSection->execute($nullableRequest->setContent($content), $presenter);

        return $this->json(...$presenter->getJson());
    }

    #[Route(
        '/ductnetworks/{ductNetworkId}/ductsections/{ductSectionId}',
        name: 'app_apd_updateductSection',
        methods: ['PUT'],
        requirements: ['ductNetworkId' => '\d+', 'ductSectionId' => '\d+']
    )]
    public function updateDuctSection(
        int $ductNetworkId,
        int $ductSectionId,
        Request $request,
        UpdateDuctSection $updateDuctSection,
        UpdateDuctSectionJsonPresenter $presenter
    ): JsonResponse
    {
        $content = json_decode($request->getContent(), true);

        $nullableRequest = new UpdateDuctSectionRequest($ductNetworkId, $ductSectionId);

        $updateDuctSection->execute($nullableRequest->setContent($content), $presenter);
        
        return $this->json(...$presenter->getJson());
    }

    #[Route(
        '/ductnetworks/{ductNetworkId}/ductsections/{ductSectionId}',
        name: 'app_apd_removeductSection',
        methods: ['DELETE'],
        requirements: ['ductNetworkId' => '\d+', 'ductSectionId' => '\d+']
    )]
    public function removeDuctSection(
        int $ductNetworkId,
        int $ductSectionId,
        RemoveDuctSection $removeDuctSection,
        RemoveDuctSectionJsonPresenter $presenter
    ): JsonResponse
    {
        $removeDuctSection->execute(new RemoveDuctSectionRequest($ductNetworkId, $ductSectionId), $presenter);

        return $this->json(...$presenter->getJson());
    }

    #[Route(
        '/projects/{id}/ductNetworks',
        name: 'app_apd_getallductnetworks',
        methods: ['GET'],
        requirements: ['id' => '\d+']
    )]
    public function getAllDuctNetworks(
        int $id,
        GetAllDuctNetworks $getAllDuctNetworks,
        GetAllDuctNetworksJsonPresenter $presenter
    )
    {
        $getAllDuctNetworks->execute(new GetAllDuctNetworksRequest($id), $presenter);

        return $this->json(...$presenter->getJson());
    }
    
    #[Route(
        '/projects/{projectId}/ductnetworks/{ductNetworkId}',
        name: 'app_apd_getductnetwork',
        methods: ['GET'],
        requirements: ['projectId' => '\d+', 'ductProjectId' => '\d+']
    )]
    public function getDuctNetwork(
        int $projectId,
        int $ductNetworkId,
        GetDuctNetwork $getDuctNetwork,
        GetDuctNetworkJsonPresenter $presenter
    )
    {
        $getDuctNetwork->execute(new GetDuctNetworkRequest($projectId, $ductNetworkId), $presenter);

        return $this->json(...$presenter->getJson());
    }
    
    #[Route(
        '/projects/{projectId}/ductnetworks',
        name: 'app_apd_addductnetwork',
        methods: ['POST'],
        requirements: ['projectId' => '\d+']
    )]
    public function addDuctNetwork(
        int $projectId,
        Request $request,
        AddDuctNetwork $addDuctNetwork,
        AddDuctNetworkJsonPresenter $presenter
    )
    {
        $content = json_decode($request->getContent(), true);

        $nullableRequest = new AddDuctNetworkRequest($projectId);
        
        $addDuctNetwork->execute($nullableRequest->setContent($content), $presenter);

        return $this->json(...$presenter->getJson());
    }

    #[Route(
        '/projects/{projectId}/ductnetworks/{ductNetworkId}',
        name: 'app_apd_updateductnetwork',
        methods: ['PUT'],
        requirements: ['projectId' => '\d+', 'ductNetworkId' => '\d+']
    )]
    public function updateDuctNetwork(
        int $projectId,
        int $ductNetworkId,
        Request $request,
        UpdateDuctNetwork $updateDuctNetwork,
        UpdateDuctNetworkJsonPresenter $presenter
    )
    {
        $content = json_decode($request->getContent(), true);

        $nullableRequest = new UpdateDuctNetworkRequest($projectId, $ductNetworkId);

        $updateDuctNetwork->execute($nullableRequest->setContent($content), $presenter);

        return $this->json(...$presenter->getJson());
    }

    #[Route(
        '/projects/{projectId}/ductnetworks/{ductNetworkId}',
        name: 'app_apd_removeductnetwork',
        methods: ['DELETE'],
        requirements: ['projectId' => '\d+', 'ductNetworkId' => '\d+']
    )]
    public function removeDuctNetwork(
        int $projectId,
        int $ductNetworkId,
        RemoveDuctNetwork $removeDuctNetwork,
        RemoveDuctNetworkJsonPresenter $presenter
    )
    {
        $removeDuctNetwork->execute(new RemoveDuctNetworkRequest($projectId, $ductNetworkId), $presenter);

        return $this->json(...$presenter->getJson());
    }

    #[Route(
        '/users/{id}/projects',
        name: 'app_apd_getallprojects',
        methods: ['GET'],
        requirements: ['id' => '\d+']
    )]
    public function getAllProjects(
        int $id,
        GetAllProjects $getAllProjects,
        GetAllProjectsJsonPresenter $presenter
    )
    {
        $getAllProjects->execute(new GetAllProjectsRequest($id), $presenter);

        return $this->json(...$presenter->getJson());
    }
    
    #[Route(
        '/users/{userId}/projects/{projectId}',
        name: 'app_apd_getproject',
        methods: ['GET'],
        requirements: ['userId' => '\d+', 'projectId' => '\d+']
    )]
    public function getProject(
        int $userId,
        int $projectId,
        GetProject $getProject,
        GetProjectJsonPresenter $presenter
    )
    {
        $getProject->execute(new GetProjectRequest($userId, $projectId), $presenter);

        return $this->json(...$presenter->getJson());
    }
    
    #[Route(
        '/users/{id}/projects',
        name: 'app_apd_addproject',
        methods: ['POST'],
        requirements: ['id' => '\d+']
    )]
    public function addProject(
        int $id,
        Request $request,
        AddProject $addProject,
        AddProjectJsonPresenter $presenter
    )
    {
        $content = json_decode($request->getContent(), true);
        $AddProjectRequest = new AddProjectRequest($id);
        
        $addProject->execute($AddProjectRequest->setContent($content), $presenter);

        return $this->json(...$presenter->getJson());
    }

    #[Route(
        '/users/{userId}/projects/{projectId}',
        name: 'app_apd_updateproject',
        methods: ['PUT'],
        requirements: ['userId' => '\d+', 'projectId' => '\d+']
    )]
    public function updateProject(
        int $userId,
        int $projectId,
        Request $request,
        UpdateProject $updateProject,
        UpdateProjectJsonPresenter $presenter
    )
    {
        $content = json_decode($request->getContent(), true);

        $updateProjectRequest = new UpdateProjectRequest($userId, $projectId);

        $updateProject->execute($updateProjectRequest->setContent($content), $presenter);

        return $this->json(...$presenter->getJson());
    }

    #[Route(
        '/users/{userId}/projects/{projecId}',
        name: 'app_apd_removeproject',
        methods: ['DELETE'],
        requirements: ['userId' => '\d+', 'projectId' => '\d+']
    )]
    public function removeProject(
        int $userId,
        int $projectId,
        RemoveProject $removeProject,
        RemoveProjectJsonPresenter $presenter
    )
    {
        $removeProject->execute(new RemoveProjectRequest($userId, $projectId), $presenter);

        return $this->json(...$presenter->getJson());
    }
}