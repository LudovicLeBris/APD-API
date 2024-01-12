<?php

namespace App\Controller\Api\Apd;

use App\Controller\Api\ApiAbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use OpenApi\Attributes as OA;
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

#[OA\Tag(
    name:"Duct section",
    description:"Manage duct sections"
)]
#[Route('/api/V1/apd')]
class DuctSectionController extends ApiAbstractController
{
    #[OA\Get(
        security:["JWT"],
        tags:["Duct section"],
        path:"/apd/ductnetworks/{id}/ductsections",
        summary:"Get all duct sections",
        description:"Get all duct sections from a duct network"
        ),
        OA\Parameter(ref:"#/components/parameters/id"),
        OA\Response(
            response:"200",
            description:"Success to get all duct sections",
            content: new OA\JsonContent(
                properties:[
                    new OA\Property(property:"message", type:"string", example:"success"),
                    new OA\Property(property:"content", type:"array", items:
                            new OA\Items(ref:"#/components/schemas/ductSection")
                    )
                ]
            )
        ),
        OA\Response(response:"404", ref:"#/components/responses/notFound"),
    ]
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
    ): JsonResponse
    {
        $getAllDuctSections->execute(new GetAllDuctSectionsRequest($id), $presenter);

        return $this->json(...$presenter->getJson());
    }
    
    #[OA\Get(
        security:["JWT"],
        tags:["Duct section"],
        path:"/apd/ductnetworks/{ductNetworkId}/ductsections/{ductSectionId}",
        summary:"Get one duct section",
        description:"Get one duct section with his id from a duct network"
        ),
        OA\Parameter(ref:"#/components/parameters/ductNetworkId"),
        OA\Parameter(ref:"#/components/parameters/ductSectionId"),
        OA\Response(
            response:"200",
            description:"Success to get one duct section",
            content: new OA\JsonContent(
                properties:[
                    new OA\Property(property:"message", type:"string", example:"success"),
                    new OA\Property(property:"content", ref:"#/components/schemas/ductSection")
                ]
            )
        ),
        OA\Response(response:"404", ref:"#/components/responses/notFound")
    ]
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
    ): JsonResponse
    {
        $getDuctSection->execute(new GetDuctSectionRequest($ductNetworkId, $ductSectionId), $presenter);

        return $this->json(...$presenter->getJson());

    }

    #[OA\Post(
        security:["JWT"],
        tags:["Duct section"],
        path:"/apd/ductnetworks/{ductNetworkId}/ductsections",
        summary:"Add a duct section",
        description:"Add a duct section in a duct network"
        ),
        OA\Parameter(ref:"#/components/parameters/ductNetworkId"),
        OA\RequestBody(ref:"#/components/requestBodies/addDuctSection"),
        OA\Response(
            response:"200",
            description:"Success to add a duct section",
            content: new OA\JsonContent(
                properties:[
                    new OA\Property(property:"message", type:"string", example:"success"),
                    new OA\Property(property:"content", ref:"#/components/schemas/ductSection")
                ]
            )
        ),
        OA\Response(response:"404", ref:"#/components/responses/notFound"),
        OA\Response(response:"422", ref:"#/components/responses/unprocessableEntity")
    ]
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

    #[OA\Patch(
        security:["JWT"],
        tags:["Duct section"],
        path:"/apd/ductnetworks/{ductNetworkId}/ductsections/{ductSectionId}",
        summary:"Update a duct section",
        description:"Update a duct section in a duct network"
        ),
        OA\Parameter(ref:"#/components/parameters/ductNetworkId"),
        OA\Parameter(ref:"#/components/parameters/ductSectionId"),
        OA\RequestBody(ref:"#/components/requestBodies/updateDuctSection"),
        OA\Response(
            response:"200",
            description:"Success to update a duct section",
            content: new OA\JsonContent(
                properties:[
                    new OA\Property(property:"message", type:"string", example:"success"),
                    new OA\Property(property:"content", ref:"#/components/schemas/ductSection")
                ]
            )
        ),
        OA\Response(response:"404", ref:"#/components/responses/notFound"),
        OA\Response(response:"422", ref:"#/components/responses/unprocessableEntity")
    ]
    #[Route(
        '/ductnetworks/{ductNetworkId}/ductsections/{ductSectionId}',
        name: 'app_apd_updateductSection',
        methods: ['PATCH'],
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

    #[OA\Delete(
        security:["JWT"],
        tags:["Duct section"],
        path:"/apd/ductnetworks/{ductNetworkId}/ductsections/{ductSectionId}",
        summary:"Remove a duct section",
        description:"Remove a duct section in a duct network"
        ),
        OA\Parameter(ref:"#/components/parameters/ductNetworkId"),
        OA\Parameter(ref:"#/components/parameters/ductSectionId"),
        OA\Response(
            response:"200",
            description:"Success to remove a duct section",
            content: new OA\JsonContent(
                properties:[
                    new OA\Property(property:"message", type:"string", example:"DuctSection with name \"section DE\" has been deleted."),
                    new OA\Property(property:"content", ref:"#/components/schemas/ductSection")
                ]
            )
        ),
        OA\Response(response:"404", ref:"#/components/responses/notFound"),
    ]
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
}