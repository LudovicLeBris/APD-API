<?php

namespace App\Controller\Api;

use OpenApi\Attributes as OA;
use App\Presentation\JsonModel;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Domain\AppUser\UseCase\Register\Register;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Presentation\AppUser\RegisterJsonPresenter;
use App\Domain\AppUser\UseCase\GetAppUser\GetAppUser;
use App\Presentation\AppUser\GetAppUserJsonPresenter;
use App\Presentation\AppUser\LostPasswordJsonPresenter;
use App\Domain\AppUser\UseCase\Register\RegisterRequest;
use App\Presentation\AppUser\RemoveAppUserJsonPresenter;
use App\Presentation\AppUser\UpdateAppUserJsonPresenter;
use App\Domain\AppUser\UseCase\LostPassword\LostPassword;
use App\Presentation\AppUser\UpdatePasswordJsonPresenter;
use App\Presentation\AppUser\ConfirmRegisterJsonPresenter;
use App\Domain\AppUser\UseCase\RemoveAppUser\RemoveAppUser;
use App\Domain\AppUser\UseCase\UpdateAppUser\UpdateAppUser;
use App\Domain\AppUser\UseCase\GetAppUser\GetAppUserRequest;
use App\Domain\AppUser\UseCase\UpdatePassword\UpdatePassword;
use App\Domain\AppUser\UseCase\ConfirmRegister\ConfirmRegister;
use App\Domain\AppUser\UseCase\LostPassword\LostPasswordRequest;
use App\Domain\AppUser\UseCase\RemoveAppUser\RemoveAppUserRequest;
use App\Domain\AppUser\UseCase\UpdateAppUser\UpdateAppUserRequest;
use App\Domain\AppUser\UseCase\UpdatePassword\UpdatePasswordRequest;
use App\Domain\AppUser\UseCase\ConfirmRegister\ConfirmRegisterRequest;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

#[OA\Tag(
    name:"App user",
    description:"Manage user's app"
)]
#[Route('/api/V1')]
class AppUserController extends ApiAbstractController
{
    private $token;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->token = $tokenStorage;
    }

    #[OA\Get(
        security:["bearerAuth"],
        tags:["App user"],
        path:"/users/{id}",
        summary:"Get user datas",
        description:"Get the user's datas"
        ),
        OA\Parameter(ref:"#/components/parameters/id"),
        OA\Response(
            response:"200",
            description:"Get user",
            content: new OA\JsonContent(
                properties:[
                    new OA\Property(property:"message", type:"string", example:"success"),
                    new OA\Property(property:"content", ref:"#/components/schemas/appUser")
                ]
            )
        ),
        OA\Response(response:"404", ref:"#/components/responses/notFound")
    ]
    #[Route(
        '/users/{id}',
        name: 'app_appuser_getappAppUser',
        methods: ['GET'],
        requirements: ['id' => '\d+']
    )]
    public function getAppUser(
        int $id,
        GetAppUser $getAppUser,
        GetAppUserJsonPresenter $presenter
    ): JsonResponse
    {
        $getAppUser->execute(new GetAppUserRequest($id), $presenter);

        return $this->json(...$presenter->getJson());
    }

    #[OA\Get(
        security:["bearerAuth"],
        tags:["App user"],
        path:"/me",
        summary:"authenticated user's datas",
        description:"Get the authenticated user's data"
        ),
        OA\Response(
            response:"200",
            description:"Get user",
            content: new OA\JsonContent(
                properties:[
                    new OA\Property(property:"message", type:"string", example:"success"),
                    new OA\Property(property:"content", ref:"#/components/schemas/appUser")
                ]
            )
        )
    ]
    #[Route(
        '/users/me',
        name: 'app_appuser_me',
        methods: ['GET']
    )]
    public function me(
        GetAppUser $getAppUser,
        GetAppUserJsonPresenter $presenter
    ): JsonResponse
    {
        if ($this->token->getToken()) {
            $id = $this->token->getToken()->getUser()->getId();
            $getAppUser->execute(new GetAppUserRequest($id), $presenter);
        } else {
            return $this->json(['code' => 401, 'message' => 'JWT Token not found'], HttpFoundationResponse::HTTP_UNAUTHORIZED);
        }

        return $this->json(...$presenter->getJson());
    }

    #[OA\Post(
        tags:["App user"],
        path:"/register",
        summary:"Register user",
        description:"Register request for a visitor who wants a account to use the application."
        ),
        OA\RequestBody(ref:"#/components/requestBodies/register"),
        OA\Response(
            response:"200",
            description:"Register user",
            content: new OA\JsonContent(
                properties:[
                    new OA\Property(property:"message", type:"string", example:"success"),
                    new OA\Property(property:"content", ref:"#/components/schemas/appUser")
                ]
            )
        )
    ]
    #[Route(
        '/register',
        name: 'app_appuser_register',
        methods: ['POST']
    )]
    public function register(
        Request $request,
        Register $register,
        RegisterRequest $registerRequest,
        RegisterJsonPresenter $presenter
    ): JsonResponse
    {
        $content = json_decode($request->getContent(), true);
        $register->execute($registerRequest->setContent($content), $presenter);

        return $this->json(...$presenter->getJson());
    }

    #[OA\Post(
        tags:["App user"],
        path:"/register/{id}",
        summary:"Confirm register",
        description:"Confirm register from a user with the email sent on registration."
        ),
        OA\Parameter(ref:"#/components/parameters/id"),
        OA\Response(
            response:"200",
            description:"Confirm user's registration",
            content: new OA\JsonContent(
                properties:[
                    new OA\Property(property:"message", type:"string", example:"User correctly activated"),
                    new OA\Property(property:"content", type:"array", example:[], items: new OA\Items())
                    ]
                    )
                ),
        OA\Response(
            response:"409",
            description:"User already registrated",
            content: new OA\JsonContent(
                properties:[
                    new OA\Property(property:"message", type:"string", example:"error"),
                    new OA\Property(property:"content", type:"array", items: new OA\Items(
                        properties:[
                            new OA\Property(property:"field", type:"string", example:"user"),
                            new OA\Property(property:"message", type:"string", example:"User is already active")
                        ]
                    ))
                ]
            )
        ),
        OA\Response(response:"404", ref:"#/components/responses/notFound")
    ]
    #[Route(
        '/register/{id}',
        name: 'app_appuser_confirmregister',
        methods: ['POST'],
        requirements: ['id' => '\d+']
    )]
    public function confirmRegister(
        int $id,
        ConfirmRegister $confirmRegister,
        ConfirmRegisterJsonPresenter $presenter
    ): JsonResponse
    {
        $confirmRegister->execute(new ConfirmRegisterRequest($id), $presenter);

        return $this->json(...$presenter->getJson());
    }

    #[OA\Post(
        tags:["App user"],
        path:"/login",
        summary:"Login",
        description:"Login request to authentifiate user."
        ),
        OA\RequestBody(ref:"#/components/requestBodies/login"),
        OA\Response(
            response:"200",
            description:"Login",
            content: new OA\JsonContent(
                properties:[
                    new OA\Property(property:"token",title:"token", type:"string", example:"eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJpYXQiOjE3MDMzNDY2MjQsImV4cCI6MTcwMzQzMzAyNCwicm9sZXMiOlsiUk9MRV9VU0VSIl0sInVzZXJuYW1lIjoidGVzdEBlbWFpbC50ZXN0In0.H-ZOfO9x5uH1VKo33s4Nb1UXcKG5ZIlaayu9OyRdeuMDcvMiEgozizgR9l-63G0uNkDOpOcXE4aeXk7RH0Lwj7c8OZN-KHxkYYcIDIJ-dDM4I72ur2z87EW9K0VhjtUxiCylzlMKcybHG1idixyGeaOTHx0fAH5KLT6KBsFgbboZoIKc9TLjH59eNBiLob0r_fssLdpEDqhWA4AWgN7NHRmIg7EXbEu54QEXPs3n7_vvN_QKkR6YltoyQDQ4Lj5dmWpYtcZOLNi9fLq91VOjFB0gTTuyS-SviOxuXtoJzgPbDaf6Q7BUYlBgDRk2Alegru9IQggHeMjBm0IG3Sk0Cg"),
                ]
            )
        ),
        OA\Response(
            response:"401",
            description:"Unauthorized",
            content: new OA\JsonContent(
                properties:[
                    new OA\Property(property:"code", title:"code http", type:"integer", example:401),
                    new OA\Property(property:"message", type:"string", example:"Invalid credentials.")
                ]
            )
        ),
        OA\Response(response:"404", ref:"#/components/responses/notFound")
    ]
    #[Route(
        '/login',
        name: 'app_appuser_login',
        methods: ['POST']
    )]
    public function login(): JsonResponse
    {
        return $this->json('email or password is missing', 400);
    }

    #[OA\Patch(
        security:["bearerAuth"],
        tags:["App user"],
        path:"/users/{id}",
        summary:"Update user",
        description:"Update the user's datas."
        ),
        OA\Parameter(ref:"#/components/parameters/id"),
        OA\RequestBody(ref:"#/components/requestBodies/updateAppUser"),
        OA\Response(
            response:"200",
            description:"Update an app user",
            content: new OA\JsonContent(
                properties:[
                    new OA\Property(property:"message", type:"string", example:"success"),
                    new OA\Property(property:"content", ref:"#/components/schemas/appUser")
                ]
            )
        ),
        OA\Response(response:"404", ref:"#/components/responses/notFound"),
        OA\Response(response:"422", ref:"#/components/responses/unprocessableEntity")
    ]
    #[Route(
        '/users/{id}',
        name: 'app_appuser_updateappuser',
        methods: ['PATCH'],
        requirements: ['id' => '\d+']
    )]
    public function updateAppUser(
        int $id,
        Request $request,
        UpdateAppUser $updateAppUser,
        UpdateAppUserJsonPresenter $presenter
    ): JsonResponse
    {
        $content = json_decode($request->getContent(), true);

        $UpdateAppUserRequest = new UpdateAppUserRequest($id);

        $updateAppUser->execute($UpdateAppUserRequest->setContent($content), $presenter);

        return $this->json(...$presenter->getJson());
    }

    #[OA\Delete(
        security:["bearerAuth"],
        tags:["App user"],
        path:"/users/{id}",
        summary:"Remove user",
        description:"Perform a full removing of user's data and all entity related (projects, duct networks and duct sections)."
        ),
        OA\Parameter(ref:"#/components/parameters/id"),
        OA\Response(
            response:"200",
            description:"Remove an app user",
            content: new OA\JsonContent(
                properties:[
                    new OA\Property(property:"message", type:"string", example:"User \"email@email.test\" and all projects are deleted."),
                    new OA\Property(property:"content", ref:"#/components/schemas/appUser")
                ]
            )
        ),
        OA\Response(response:"404", ref:"#/components/responses/notFound"),
    ]
    #[Route(
        '/users/{id}',
        name: 'app_appuser_removeappuser',
        methods: ['DELETE'],
        requirements: ['id' => '\d+']
    )]
    public function removeAppUser(
        int $id,
        RemoveAppUser $removeAppUser,
        RemoveAppUserJsonPresenter $presenter
    ): JsonResponse
    {
        $removeAppUser->execute(new RemoveAppUserRequest($id), $presenter);

        return $this->json(...$presenter->getJson());
    }

    #[OA\Post(
        tags:["App user"],
        path:"/lostpassword",
        summary:"Lost password",
        description:"The user can perform a \"Lost password\" request. The sytem send an email to the recipient supplied in the request with a guid identifier of a recover entity save in database. The user's account is disabled."
        ),
        OA\RequestBody(ref:"#/components/requestBodies/lostPassword"),
        OA\Response(
            response:"200",
            description:"Lost password request",
            content: new OA\JsonContent(
                properties:[
                    new OA\Property(property:"message", type:"string", example:"An email was sent to \"email@email.test\" with a recovery link."),
                    new OA\Property(property:"content", type:"array", example:[], items:new OA\Items())
                ]
            )
        ),
        OA\Response(response:"422", ref:"#/components/responses/unprocessableEntity"),
        OA\Response(response:"404", ref:"#/components/responses/notFound"),
        OA\Response(
            response:"423",
            description:"User disabled",
            content: new OA\JsonContent(
                properties:[
                    new OA\Property(property:"message", type:"string", example:"error"),
                    new OA\Property(property:"content", type:"array", items:
                        new OA\Items(
                            properties:[
                                new OA\Property(property:"field", type:"string", example:"email"),
                                new OA\Property(property:"message", type:"string", example:"User is not enable")
                            ]
                        )
                    )
                ]
            )
        )
    ]
    #[Route(
        '/lostpassword',
        name: 'app_appuser_lostpassword',
        methods: ['POST'],
    )]
    public function lostPassword(
        Request $request,
        LostPassword $lostPassword,
        LostPasswordJsonPresenter $presenter
    ): JsonResponse
    {
        $content = json_decode($request->getContent(), true);

        if (!array_key_exists('email', $content) || is_null($content)) {
            $jsonModel = new JsonModel(
                'error',
                ['Email is missing'],
                422
            );
            return $this->json(...$jsonModel->getJsonResponse());
        }

        $lostPassword->execute(new LostPasswordRequest($content['email']), $presenter);

        return $this->json(...$presenter->getJson());
    }

    #[OA\Patch(
        tags:["App user"],
        path:"/recoverpassword/{guid}",
        summary:"Recover password",
        description:"Recover password after a \"Lost password\" request. The guid is provided in the mail received by user."
        ),
        OA\Parameter(
            name:"guid",
            in:"path",
            description:"Ressource's guid",
            required:"true",
            schema: new OA\Schema(type:"string")
        ),
        OA\RequestBody(ref:"#/components/requestBodies/recoverPassword"),
        OA\Response(
            response:"200",
            description:"Password updated successfully.",
            content: new OA\JsonContent(
                properties:[
                    new OA\Property(property:"message", type:"string", example:"success"),
                    new OA\Property(property:"content", type:"array", items: new OA\Items(
                        type:"string", example:"Password updated"
                    ))
                ]
            )
        ),
        OA\Response(response:"404", ref:"#/components/responses/notFound"),
        OA\Response(response:"422", ref:"#/components/responses/unprocessableEntity"),
    ]
    #[Route(
        '/recoverpassword/{guid}',
        name: 'app_appuser_recoverpassword',
        methods: ['PATCH'],
        requirements: ['guid' => '(?:\d|[a-z]){16}']
    )]
    public function recoverPassword(
        string $guid,
        Request $request,
        UpdatePassword $updatePassword,
        UpdatePasswordJsonPresenter $presenter
    ): JsonResponse
    {
        $content = json_decode($request->getContent(), true);

        $UpdatePasswordRequest = (new UpdatePasswordRequest())->setGuid($guid);

        $updatePassword->execute($UpdatePasswordRequest->setContent($content), $presenter);

        return $this->json(...$presenter->getJson());
    }

    #[OA\Patch(
        security:["bearerAuth"],
        tags:["App user"],
        path:"/users/{id}/updatepassword",
        summary:"Update password",
        description:"The user can update his password in a separated request. I must provide his old password."
        ),
        OA\Parameter(ref:"#/components/parameters/id"),
        OA\RequestBody(ref:"#/components/requestBodies/updatePassword"),
        OA\Response(
            response:"200",
            description:"Password updated successfully.",
            content: new OA\JsonContent(
                properties:[
                    new OA\Property(property:"message", type:"string", example:"success"),
                    new OA\Property(property:"content", type:"array", items: new OA\Items(
                        type:"string", example:"Password updated"
                    ))
                ]
            )
        ),
        OA\Response(response:"404", ref:"#/components/responses/notFound"),
        OA\Response(response:"422", ref:"#/components/responses/unprocessableEntity"),
    ]
    #[Route(
        '/users/{id}/updatepassword',
        name: 'app_appuser_updatepassword',
        methods: ['PATCH'],
        requirements: ['id' => '\d+']
    )]
    public function updatePassword(
        int $id,
        Request $request,
        UpdatePassword $updatePassword,
        UpdatePasswordJsonPresenter $presenter
    ): JsonResponse
    {
        $content = json_decode($request->getContent(), true);

        $UpdatePasswordRequest = (new UpdatePasswordRequest())->setId($id);

        $updatePassword->execute($UpdatePasswordRequest->setContent($content), $presenter);

        return $this->json(...$presenter->getJson());
    }
}