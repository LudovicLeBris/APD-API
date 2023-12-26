<?php

namespace App\Controller\Api\Apd;

use App\Controller\Api\ApiAbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use OpenApi\Attributes as OA;
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

#[OA\Tag(
    name:"Project",
    description:"Manage projects to add duct networks"
)]
#[Route('/api/V1/apd')]
class ProjectController extends ApiAbstractController
{
    #[OA\Get(
        security:["JWT"],
        tags:["Project"],
        path:"/apd/users/{id}/projects",
        summary:"Get all project",
        description:"Get all the projects from an user."
        ),
        OA\Parameter(ref:"#/components/parameters/id"),
        OA\Response(
            response:"200",
            description:"Success to get all projects",
            content: new OA\JsonContent(
                properties:[
                    new OA\Property(property:"message", type:"string", example:"success"),
                    new OA\Property(property:"content", type:"array", items:
                            new OA\Items(allOf: [new OA\Schema(ref:"#/components/schemas/project")])
                    )
                ]
            )
        ),
        OA\Response(response:"404", ref:"#/components/responses/notFound")
    ]
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
    ): JsonResponse
    {
        $getAllProjects->execute(new GetAllProjectsRequest($id), $presenter);

        return $this->json(...$presenter->getJson());
    }
    
    #[OA\Get(
        security:["JWT"],
        tags:["Project"],
        path:"/apd/users/{userId}/projects/{ProjectId}",
        summary:"Get one project",
        description:"Get one project with his id from an user account."
        ),
        OA\Parameter(ref:"#/components/parameters/userId"),
        OA\Parameter(ref:"#/components/parameters/projectId"),
        OA\Response(
            response:"200",
            description:"Success to get one project",
            content: new OA\JsonContent(
                properties:[
                    new OA\Property(property:"message", type:"string", example:"success"),
                    new OA\Property(property:"content", ref:"#/components/schemas/project")
                ]
            )
        ),
        OA\Response(response:"404", ref:"#/components/responses/notFound")
    ]
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
    ): JsonResponse
    {
        $getProject->execute(new GetProjectRequest($userId, $projectId), $presenter);

        return $this->json(...$presenter->getJson());
    }
    
    #[OA\Post(
        security:["JWT"],
        tags:["Project"],
        path:"/apd/users/{userId}/projects",
        summary:"Add a project",
        description:"Add a project in the user account."
        ),
        OA\Parameter(ref:"#/components/parameters/userId"),
        OA\RequestBody(ref:"#/components/requestBodies/addProject"),
        OA\Response(
            response:"200",
            description:"Success to add a project",
            content: new OA\JsonContent(
                properties:[
                    new OA\Property(property:"message", type:"string", example:"success"),
                    new OA\Property(property:"content", type:"object", properties: [
                        new OA\Property(property:"id", type:"integer", title:"id", description:"Project's id", example:21),
                        new OA\Property(property:"name", type:"string", title:"name", description:"Project's name", example:"project A"),
                        new OA\Property(property:"userId", type:"integer", title:"user id", description:"Project's associated user id", example:10),
                        new OA\Property(property:"generalAltitude", type:"integer", title:"general altitude", description:"Project's altitude below sea level, all duct networks and duct sections are dependant of this property - in meter (m)", example:800),
                        new OA\Property(property:"generalTemperature", type:"number", title:"general temperature", description:"Project's temperature, all duct networks and duct sections are dependant of this property - in degrees Celsius (°C)", example:18.2),
                        new OA\Property(property:"ductNetworks", type:"array", title:"duct networks", description:"All duct networks associated of this project", example:"[]", items: 
                            new OA\Items()
                        ),
                    ])
                ]
            )
        ),
        OA\Response(response:"404", ref:"#/components/responses/notFound"),
        OA\Response(response:"422", ref:"#/components/responses/unprocessableEntity")
    ]
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
    ): JsonResponse
    {
        $content = json_decode($request->getContent(), true);
        $AddProjectRequest = new AddProjectRequest($id);
        
        $addProject->execute($AddProjectRequest->setContent($content), $presenter);

        return $this->json(...$presenter->getJson());
    }

    #[OA\Patch(
        security:["JWT"],
        tags:["Project"],
        path:"/apd/users/{userId}/projects/{projectId}",
        summary:"Update a project",
        description:"Update a project in the user account"
        ),
        OA\Parameter(ref:"#/components/parameters/userId"),
        OA\Parameter(ref:"#/components/parameters/projectId"),
        OA\RequestBody(ref:"#/components/requestBodies/updateProject"),
        OA\Response(
            response:"200",
            description:"Success to update a project",
            content: new OA\JsonContent(
                properties:[
                    new OA\Property(property:"message", type:"string", example:"success"),
                    new OA\Property(property:"content", ref:"#/components/schemas/project")
                ]
            )
        ),
        OA\Response(response:"404", ref:"#/components/responses/notFound"),
        OA\Response(response:"422", ref:"#/components/responses/unprocessableEntity")
    ]
    #[Route(
        '/users/{userId}/projects/{projectId}',
        name: 'app_apd_updateproject',
        methods: ['PATCH'],
        requirements: ['userId' => '\d+', 'projectId' => '\d+']
    )]
    public function updateProject(
        int $userId,
        int $projectId,
        Request $request,
        UpdateProject $updateProject,
        UpdateProjectJsonPresenter $presenter
    ): JsonResponse
    {
        $content = json_decode($request->getContent(), true);

        $updateProjectRequest = new UpdateProjectRequest($userId, $projectId);

        $updateProject->execute($updateProjectRequest->setContent($content), $presenter);

        return $this->json(...$presenter->getJson());
    }

    #[OA\Delete(
        security:["JWT"],
        tags:["Project"],
        path:"/apd/users/{userId}/projects/{projectId}",
        summary:"Remove a project",
        description:"Remove a project in the user account. All duct networks and duct section associated will be deleted too."
        ),
        OA\Parameter(ref:"#/components/parameters/userId"),
        OA\Parameter(ref:"#/components/parameters/projectId"),
        OA\Response(
            response:"200",
            description:"Success to remove a project",
            content: new OA\JsonContent(
                properties:[
                    new OA\Property(property:"message", type:"string", example:'Project with name "Project 1" has been deleted, all associated duct networks and duct sections has been deletes too.'),
                    new OA\Property(property:"content", ref:"#/components/schemas/project")
                ]
            )
        ),
        OA\Response(response:"404", ref:"#/components/responses/notFound"),
    ]
    #[Route(
        '/users/{userId}/projects/{projectId}',
        name: 'app_apd_removeproject',
        methods: ['DELETE'],
        requirements: ['userId' => '\d+', 'projectId' => '\d+']
    )]
    public function removeProject(
        int $userId,
        int $projectId,
        RemoveProject $removeProject,
        RemoveProjectJsonPresenter $presenter
    ): JsonResponse
    {
        $removeProject->execute(new RemoveProjectRequest($userId, $projectId), $presenter);

        return $this->json(...$presenter->getJson());
    }
}