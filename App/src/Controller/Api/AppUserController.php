<?php

namespace App\Controller\Api;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Presentation\JsonModel;

use App\Domain\AppUser\UseCase\Register\Register;
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

#[Route('/api/V1')]
class AppUserController extends ApiAbstractController
{
    #[Route('/test', name: 'app_test', methods: ['GET'])]
    public function test(): JsonResponse
    {
        return $this->json('test', 200);
    }
    
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

    #[Route(
        '/login',
        name: 'app_appuser_login',
        methods: ['POST']
    )]
    public function login(): JsonResponse
    {
        return $this->json('email or password is missing', 400);
    }

    #[Route(
        '/users/{id}',
        name: 'app_appuser_updateappuser',
        methods: ['PUT'],
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

    #[Route(
        '/recoverpassword/{guid}',
        name: 'app_appuser_recoverpassword',
        methods: ['PUT'],
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

    #[Route(
        '/users/{id}/updatepassword',
        name: 'app_appuser_updatepassword',
        methods: ['PUT'],
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