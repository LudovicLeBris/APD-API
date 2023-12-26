<?php

namespace App\Controller\Api\Apd;

use App\Controller\Api\ApiAbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use OpenApi\Attributes as OA;
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

#[OA\Tag(
    name:"Duct network",
    description:"Manage duct networks to add duct sections"
)]
#[Route('/api/V1/apd')]
class DuctNetworkController extends ApiAbstractController
{
    #[OA\Get(
        security:["JWT"],
        tags:["Duct network"],
        path:"/apd/projects/{id}/ductnetworks",
        summary:"Get all duct networks",
        description:"Get all duct networks from a project"
        ),
        OA\Parameter(ref:"#/components/parameters/id"),
        OA\Response(
            response:"200",
            description:"Success to get all duct networks",
            content: new OA\JsonContent(
                properties:[
                    new OA\Property(property:"message", type:"string", example:"success"),
                    new OA\Property(property:"content", type:"array", items:
                            new OA\Items(allOf: [new OA\Schema(ref:"#/components/schemas/ductNetwork")])
                    )
                ]
            )
        ),
        OA\Response(response:"404", ref:"#/components/responses/notFound")
    ]
    #[Route(
        '/projects/{id}/ductnetworks',
        name: 'app_apd_getallductnetworks',
        methods: ['GET'],
        requirements: ['id' => '\d+']
    )]
    public function getAllDuctNetworks(
        int $id,
        GetAllDuctNetworks $getAllDuctNetworks,
        GetAllDuctNetworksJsonPresenter $presenter
    ): JsonResponse
    {
        $getAllDuctNetworks->execute(new GetAllDuctNetworksRequest($id), $presenter);

        return $this->json(...$presenter->getJson());
    }
    
    #[OA\Get(
        security:["JWT"],
        tags:["Duct network"],
        path:"/apd/projects/{projectId}/ductnetworks/{ductNetworkId}",
        summary:"Get one project",
        description:"Get one duct network with his id from a project"
        ),
        OA\Parameter(ref:"#/components/parameters/projectId"),
        OA\Parameter(ref:"#/components/parameters/ductNetworkId"),
        OA\Response(
            response:"200",
            description:"Success to get one duct network",
            content: new OA\JsonContent(
                properties:[
                    new OA\Property(property:"message", type:"string", example:"success"),
                    new OA\Property(property:"content", ref:"#/components/schemas/ductNetwork")
                ]
            )
        ),
        OA\Response(response:"404", ref:"#/components/responses/notFound")
    ]
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
    ): JsonResponse
    {
        $getDuctNetwork->execute(new GetDuctNetworkRequest($projectId, $ductNetworkId), $presenter);

        return $this->json(...$presenter->getJson());
    }
    
    #[OA\Post(
        security:["JWT"],
        tags:["Duct network"],
        path:"/apd/projects/{projectId}/ductnetworks",
        summary:"Add a duct network",
        description:"Add a duct network in a project"
        ),
        OA\Parameter(ref:"#/components/parameters/projectId"),
        OA\RequestBody(ref:"#/components/requestBodies/addDuctNetwork"),
        OA\Response(
            response:"200",
            description:"Success to add a duct network",
            content: new OA\JsonContent(
                properties:[
                    new OA\Property(property:"message", type:"string", example:"success"),
                    new OA\Property(property:"content", type:"object", properties: [
                        new OA\Property(property:"id", type:"integer", title:"id", description:"Duct network's id", example:42),
                        new OA\Property(property:"name", type:"string", title:"name", description:"Project's name", example:"duct network n°1"),
                        new OA\Property(property:"projectId", type:"integer", title:"project id", description:"Duct network's associated project id", example:21),
                        new OA\Property(property:"air", type:"object", title:"air", description:"Duct network's air properties", properties:[
                            new OA\Property(property:"viscosity", title:"viscosity", description:"Viscosity property of the air", type:"number", format:"float", example:1.5080510051843115e-5),
                            new OA\Property(property:"density", title:"density", description:"Density property of the air", type:"number", format:"float", example:1.2058928673556562),
                            new OA\Property(property:"temperature", title:"temperature", description:"Temperature property of the air", type:"number", format:"float", example:18.2),
                            new OA\Property(property:"altitude", title:"altitude", description:"Altitude property of the air", type:"integer", example:800),
                        ]),
                        new OA\Property(property:"altitude", type:"integer", title:"altitude", description:"duct network's altitude below sea level, all duct sections are dependant of this property - in meter (m)", example:800),
                        new OA\Property(property:"temperature", type:"number", title:"temperature", description:"duct network's temperature, all duct sections are dependant of this property - in degrees Celsius (°C)", example:18.2),
                        new OA\Property(property:"generalMaterial", title:"general material", description:"Duct network's material, all duct sections are dependent of this property", type:"string",example:"galvanised_steel"),
                        new OA\Property(property:"ductSections", type:"array", title:"duct sections", description:"All duct sections associated of this project", example:"[]", items: 
                            new OA\Items()
                        ),
                        new OA\Property(property:"totalLinearApd", type:"number", format:"float", title:"total linear apd", description:"Result of all linear apd calculation in this duct network - in pascal (Pa)", example:36.387),
                        new OA\Property(property:"totalSingularApd", type:"number", format:"float", title:"total singular apd", description:"Result of all singular apd calculation in this duct network - in pascal (Pa)", example:83.473),
                        new OA\Property(property:"totalAdditionalApd", type:"integer", title:"total additional apd", description:"Result of all additional apd calculation in this duct network - in pascal (Pa)", example:150),
                        new OA\Property(property:"totalApd", type:"number", format:"float", title:"total apd", description:"Result of total of all apd calculation in this duct network - in pascal (Pa)", example:269.86),
                    ])
                ]
            )
        ),
        OA\Response(response:"404", ref:"#/components/responses/notFound"),
        OA\Response(response:"422", ref:"#/components/responses/unprocessableEntity")
    ]
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
    ): JsonResponse
    {
        $content = json_decode($request->getContent(), true);

        $nullableRequest = new AddDuctNetworkRequest($projectId);
        
        $addDuctNetwork->execute($nullableRequest->setContent($content), $presenter);

        return $this->json(...$presenter->getJson());
    }

    #[OA\Patch(
        security:["JWT"],
        tags:["Duct network"],
        path:"/apd/projects/{projectId}/ductnetworks/{ductNetworkId}",
        summary:"Update a duct network",
        description:"Update a duct network in a project"
        ),
        OA\Parameter(ref:"#/components/parameters/projectId"),
        OA\Parameter(ref:"#/components/parameters/ductNetworkId"),
        OA\RequestBody(ref:"#/components/requestBodies/updateDuctNetwork"),
        OA\Response(
            response:"200",
            description:"Success to update a duct network",
            content: new OA\JsonContent(
                properties:[
                    new OA\Property(property:"message", type:"string", example:"success"),
                    new OA\Property(property:"content", ref:"#/components/schemas/ductNetwork")
                ]
            )
        ),
        OA\Response(response:"404", ref:"#/components/responses/notFound"),
        OA\Response(response:"422", ref:"#/components/responses/unprocessableEntity")
    ]
    #[Route(
        '/projects/{projectId}/ductnetworks/{ductNetworkId}',
        name: 'app_apd_updateductnetwork',
        methods: ['PATCH'],
        requirements: ['projectId' => '\d+', 'ductNetworkId' => '\d+']
    )]
    public function updateDuctNetwork(
        int $projectId,
        int $ductNetworkId,
        Request $request,
        UpdateDuctNetwork $updateDuctNetwork,
        UpdateDuctNetworkJsonPresenter $presenter
    ): JsonResponse
    {
        $content = json_decode($request->getContent(), true);

        $nullableRequest = new UpdateDuctNetworkRequest($projectId, $ductNetworkId);

        $updateDuctNetwork->execute($nullableRequest->setContent($content), $presenter);

        return $this->json(...$presenter->getJson());
    }

    #[OA\Delete(
        security:["JWT"],
        tags:["Duct network"],
        path:"/apd/projects/{projectId}/ductnetworks/{ductNetworkId}",
        summary:"Remove a duct network",
        description:"Remove a duct network in a project. All duct sections associated will be deleted too."
        ),
        OA\Parameter(ref:"#/components/parameters/projectId"),
        OA\Parameter(ref:"#/components/parameters/ductNetworkId"),
        OA\Response(
            response:"200",
            description:"Success to remove a duct network",
            content: new OA\JsonContent(
                properties:[
                    new OA\Property(property:"message", type:"string", example:"DuctNetwork with name \"duct network n°1\" has been deleted, all associated duct sections has been deletes too."),
                    new OA\Property(property:"content", ref:"#/components/schemas/ductNetwork")
                ]
            )
        ),
        OA\Response(response:"404", ref:"#/components/responses/notFound"),
    ]
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
    ): JsonResponse
    {
        $removeDuctNetwork->execute(new RemoveDuctNetworkRequest($projectId, $ductNetworkId), $presenter);

        return $this->json(...$presenter->getJson());
    }

}