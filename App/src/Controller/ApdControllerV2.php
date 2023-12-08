<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

use App\Domain\Apd\Factory\DuctSectionFactory;
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

use App\Domain\Apd\Entity\DuctNetwork;
use App\Domain\Apd\Entity\Project;
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

#[Route('/api/V2')]
class ApdControllerV2 extends AbstractController
{
    #[Route('/ductsection/{id}',
        name: 'app_apdV2_test',
        methods: ['GET'],
        requirements: ['id' => '\d+']
    )]
    public function test(
        int $id,
        GetDuctSection $getDuctSection,
        GetDuctSectionJsonPresenter $presenter
    )
    {
        $getDuctSection->execute(new GetDuctSectionRequest($id), $presenter);

        return $this->json(...$presenter->getJson());

    }

    #[Route('/test', name: 'app_apd_test2', methods:['GET'])]
    public function test2(): JsonResponse
    {
        $ductNetwork = new DuctNetwork("A", "galvanised_steel", 20);
        $ductNetwork->setId(1);

        $ductSectionFactory = new DuctSectionFactory();
        
        $ductSectionFactory->setSectionTechnicalDatas([
            "air" => $ductNetwork->getAir(),
            "shape" => 'rectangular',
            "material" => $ductNetwork->getGeneralMaterial(),
            "flowrate" => 5000,
            "length" => 10,
            "singularities" => [
                "90_elbow" => 1,
                "90_junc_tee" => 1
            ],
            "additionalApd" => 10,
            "width" => 500,
            "height" => 300
        ]);
        $ductSection = $ductSectionFactory->createDuctSection()->setId(1);
        $ductSection->setName("A")->setDuctNetworkId($ductNetwork->getId());
        
        $ductSectionFactory->setSectionTechnicalDatas([
            "air" => $ductNetwork->getAir(),
            "shape" => 'circular',
            "material" => $ductNetwork->getGeneralMaterial(),
            "flowrate" => 2000,
            "length" => 1,
            "singularities" => [
                "90_elbow" => 1,
            ],
            "additionalApd" => 20,
            "diameter" => 315,
        ]);
        $ductSection2 = $ductSectionFactory->createDuctSection()->setId(2);
        $ductSection2->setName("B")->setDuctNetworkId($ductNetwork->getId());
        
        $ductNetwork->addDuctSection($ductSection);
        $ductNetwork->addDuctSection($ductSection2);
        // $ductNetwork->removeDuctSection($ductSection2);
        // $ductNetwork->setTemperature(5);

        // $response = [$ductSection->getDuctSectionDatas(), $ductSection2->getDuctSectionDatas()];
        $response = $ductNetwork;

        return $this->json(
            $response, 200
        );
    }

    #[Route('/ductnetwork/{ductNetworkId}/ductsection',
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

    #[Route('/ductnetwork/{ductNetworkId}/ductsection/{ductSectionId}',
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

    #[Route('/ductnetwork/{ductNetworkId}/ductsection/{ductSectionId}',
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
        '/ductnetwork/{id}',
        name: 'app_apd_getductnetwork',
        methods: ['GET'],
        requirements: ['id' => '\d+']
    )]
    public function getDuctNetwork(
        int $id,
        GetDuctNetwork $getDuctNetwork,
        GetDuctNetworkJsonPresenter $presenter
    )
    {
        $getDuctNetwork->execute(new GetDuctNetworkRequest($id), $presenter);

        return $this->json(...$presenter->getJson());
    }
    
    #[Route(
        '/project/{projectId}/ductnetwork',
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
        '/project/{projectId}/ductnetwork/{ductNetworkId}',
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
        '/project/{projectId}/ductnetwork/{ductNetworkId}',
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
        '/project/{id}',
        name: 'app_apd_getproject',
        methods: ['GET'],
        requirements: ['id' => '\d+']
    )]
    public function getProject(
        int $id,
        GetProject $getProject,
        GetProjectJsonPresenter $presenter
    )
    {
        $getProject->execute(new GetProjectRequest($id), $presenter);

        return $this->json(...$presenter->getJson());
    }
    
    #[Route(
        '/project',
        name: 'app_apd_addproject',
        methods: ['POST']
    )]
    public function addProject(
        Request $request,
        AddProject $addProject,
        AddProjectRequest $nullableRequest,
        AddProjectJsonPresenter $presenter
    )
    {
        $content = json_decode($request->getContent(), true);
        
        $addProject->execute($nullableRequest->setContent($content), $presenter);

        return $this->json(...$presenter->getJson());
    }

    #[Route(
        '/project/{id}',
        name: 'app_apd_updateproject',
        methods: ['PUT'],
        requirements: ['id' => '\d+']
    )]
    public function updateProject(
        int $id,
        Request $request,
        UpdateProject $updateProject,
        UpdateProjectJsonPresenter $presenter
    )
    {
        $content = json_decode($request->getContent(), true);

        $nullableRequest = new UpdateProjectRequest($id);

        $updateProject->execute($nullableRequest->setContent($content), $presenter);

        return $this->json(...$presenter->getJson());
    }

    #[Route(
        '/project/{id}',
        name: 'app_apd_removeproject',
        methods: ['DELETE'],
        requirements: ['id' => '\d+']
    )]
    public function removeProject(
        int $id,
        RemoveProject $removeProject,
        RemoveProjectJsonPresenter $presenter
    )
    {
        $removeProject->execute(new RemoveProjectRequest($id), $presenter);

        return $this->json(...$presenter->getJson());
    }
}