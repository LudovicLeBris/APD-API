<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use OpenApi\Attributes as OA;

#[OA\Info(
    title: "APD-API",
    version:"0.1",
    description:"An API to perform air pressure drop calculation through projects and user account"
    )]
#[OA\Server(url:"https://aeraulic.io/api/V1", description:"Apd calculator API server")]
#[OA\Parameter(
    name:"id",
    in:"path",
    description:"Ressource's id",
    required:"true",
    schema: new OA\Schema(type:"integer")
    ),
    OA\Parameter(
        name:"userId",
        in:"path",
        description:"User's id",
        required:"true",
        schema: new OA\Schema(type:"integer")
    ),
    OA\Parameter(
        name:"projectId",
        in:"path",
        description:"Project's id",
        required:"true",
        schema: new OA\Schema(type:"integer")
    ),
    OA\Parameter(
        name:"ductNetworkId",
        in:"path",
        description:"Duct network's id",
        required:"true",
        schema: new OA\Schema(type:"integer")
    ),
    OA\Parameter(
        name:"ductSectionId",
        in:"path",
        description:"Duct section's id",
        required:"true",
        schema: new OA\Schema(type:"integer")
    ),
]
#[OA\Response(
        response:"notFound",
        description:"Ressource not found",
        content: new OA\JsonContent(
            properties:[
                new OA\Property(property:"message", type:"string", example:"Error"),
                new OA\Property(
                    property:"content",
                    type:"array",
                    items:
                        new OA\Items(
                            properties:[
                                new OA\Property(property:"field", type:"string", example:"id"),
                                new OA\Property(property:"message", type:"string", example:"Ressource doesn't exist with this id."),
                            ]
                        )
                )
            ]
        )
    ),
    OA\Response(
        response:"unprocessableEntity",
        description:"Unable to process the contained instructions",
        content: new OA\JsonContent(
            properties:[
                new OA\Property(property:"message", type:"string", example:"Error"),
                new OA\Property(
                    property:"content",
                    type:"array",
                    items:
                        new OA\Items(
                            properties:[
                                new OA\Property(property:"field", type:"string", example:"shape"),
                                new OA\Property(property:"message", type:"string", example:"Value \"rectangulard\" is not an element of the valid values: circular, rectangular."),
                            ]
                        )
                )
            ]
        )
    )
]
#[OA\SecurityScheme(
    securityScheme:"JWT",
    type:"apiKey",
    scheme:"bearer",
    name:"JWTToken",
    in:"header",
    description:"Enter JWT token",
)]
#[Route('/api/V1')]
class ApiAbstractController extends AbstractController
{
    #[Route('/test', name:'app_apitest',methods:['GET'])]
    public function apiTest(): JsonResponse
    {
        return $this->json('test ok', 200);
    }
}